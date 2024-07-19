<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Port;
use App\Models\Termostat;
use App\Models\Usensor;
use App\Repositories\ObjectRepository;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class TermostatService
{
    private $id_object;

    public function __construct(
        private TermostatObjectService $termostat_object_service,
        private PortService $port_service,
        private PortRepository $port_repository,
        private ObjectRepository $objectRepository
    ) {
    }

    /**
     * Удаление датчика температуры. Если связанный объект системный, то удаление объекта, метода, задачи расписания,
     * созданных автоматически при создании датчика температуры
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $termostat = Termostat::findOrFail($id);

        $deviceAndPort = $this->port_service
            ->getIdDeviceAndPortId($termostat->id_object);

        ConfigMegaService::setPortType(
            $deviceAndPort['id_device'],
            $this->port_repository->getNumPortByID($deviceAndPort['id_port']),
            'IN'
        );

        //В портах удаляем все упоминания о датчике температуры, порт переводим в режим IN
        Port::where('object', $termostat->id_object)
            ->update(['status' => 'IN', 'object' => null, 'comment' => '']);

        if ($termostat->iobject && $termostat->iobject->is_system) {
            DB::transaction(function () use (&$termostat) {
                //if (!HomeObject::isObjectUsed($termostat->id_object, $termostat->id, 'termostats')) {
                HomeObject::deleteAutoObject($termostat->id_object);
                //}
                $termostat->delete();
            });
        } else {
            $termostat->delete();
        }

        return true;
    }

    public function prepare(Termostat $termostat, array $data)
    {
        $placeType = $data['placetype'];

        if ($placeType == 'port' || $placeType == '1wbus') {
            $data['min_threshold'] = -55;
            $data['max_threshold'] = 125;
        } else {
            $usensorObject = $this->objectRepository->getById($data['usensor_id']);
            $usensor = $usensorObject->usensor;
            if ($usensor->type == Usensor::TYPE_HTU21D || $usensor->type == Usensor::TYPE_OUTDOORV2) {
                $data['min_threshold'] = -40;
                $data['max_threshold'] = 105;
            } elseif ($usensor->type == Usensor::TYPE_BME280 || $usensor->type == Usensor::TYPE_OUTDOORV3) {
                $data['min_threshold'] = -40;
                $data['max_threshold'] = 85;
            } elseif ($usensor->type == Usensor::TYPE_SCD40 || $usensor->type == Usensor::TYPE_SCD41) {
                $data['min_threshold'] = -10;
                $data['max_threshold'] = 60;
            } else {
                $data['min_threshold'] = 0;
                $data['max_threshold'] = 0;
            }
        }

        unset($data['object_type']);
        unset($data['device_id']);
        unset($data['port_id']);
        unset($data['placetype_radio']);

        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }

        $termostat->fill($data);
    }

    /**
     * Создание датчика температуры. Если $data['type'] === 'auto',
     * то еще создается объект с методом и событием.
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $termostat = new Termostat();

        $port_id = $data['port_id'] ?? null;
        $deviceId = $data['device_id'];
        $placeType = $data['placetype'];
        unset($data['port_id']);
        unset($data['device_id']);

        $this->prepare($termostat, $data);
        $termostat->current = null;

        DB::transaction(function () use (&$termostat, $port_id, $deviceId, $placeType) {
            $unique_name = HomeObject::getUniqueObjectName(0, $termostat->name);

            $object = $this->termostat_object_service
                ->createTermostatObject($unique_name);

            $this->termostat_object_service
                ->createTermostatObjectMethodsWithEvents($object->id);

            if ($termostat->room != null) {
                RoomService::addTermostat($termostat->room, $termostat->optimal);
            }

            $termostat->id_object = $object->id;
            $termostat->save();

            if ($port_id) {
                Port::where('id', $port_id)
                    ->update(['object' => $object->id, 'comment' => $termostat->name]);

                //Переназначаем порт на контроллере
                if ($placeType == 'port') {
                    Port::where('id', $port_id)
                        ->update(['status' => '1WIRE']);

                    ConfigMegaService::setPortType(
                        $deviceId,
                        $this->port_repository->getNumPortByID($port_id),
                        '1WIRE'
                    );
                } elseif ($placeType == '1wbus') {
                    Port::where('id', $port_id)
                        ->update(['status' => '1W-BUS']);

                    ConfigMegaService::setPortType(
                        $deviceId,
                        $this->port_repository->getNumPortByID($port_id),
                        '1W-BUS'
                    );
                }
            }
            $this->id_object = $object->id;
        });

        if ($this->id_object) {
            chdir(env('SERVER_FOLDER').'/scripts');
            exec('php check_termostat.php '.$this->id_object);
        }

        return $termostat->id;
    }

    private function isUpdateAutoObjectName(Termostat $termostat, string $name): bool
    {
        return $termostat->name !== trim($name) && $termostat->iobject && $termostat->iobject->is_system;
    }

    /**
     * Обновление датчика температуры. Если изменилось название и у датчика температуры системный объект, то
     * изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @throws \Throwable
     */
    public function update(Termostat $termostat, array $data): int
    {
        $port_id = $data['port_id'] ?? null;
        $deviceId = $data['device_id'];
        $placeType = $data['placetype'];

        DB::transaction(function () use (&$termostat, $data, $port_id, $deviceId, $placeType) {
            if ($this->isUpdateAutoObjectName($termostat, $data['name'])) {
                $termostat->iobject->name = HomeObject::getUniqueObjectName(
                    $termostat->iobject->id,
                    trim($data['name'])
                );
                $termostat->iobject->save();
            }

            //Если порт указан, меняем его в портах для этого объекта, если нет, то убираем старый в портах
            if ($data['port_id'] && (($placeType == 'port') || ($placeType == '1wbus'))) {
                //Обнуляем порт, приводим в исходное состояние
                Port::where('object', $termostat->id_object)
                    ->update(['object' => null, 'comment' => '', 'status' => 'IN']);
                // ConfigMegaService::setPortType($deviceId, $this->port_repository->getNumPortByID($port_id), 'IN');

                //Привязываем объект к порту в БД
                Port::where('id', $port_id)
                    ->update(['object' => $termostat->id_object, 'comment' => $termostat->name]);

                //Переназначаем порт на контроллере
                if ($placeType == 'port') {
                    Port::where('id', $port_id)
                        ->update(['status' => '1WIRE']);

                    ConfigMegaService::setPortType(
                        $deviceId,
                        $this->port_repository->getNumPortByID($port_id),
                        '1WIRE'
                    );
                } elseif ($placeType == '1wbus') {
                    Port::where('id', $port_id)
                        ->update(['status' => '1W-BUS']);

                    ConfigMegaService::setPortType(
                        $deviceId,
                        $this->port_repository->getNumPortByID($port_id),
                        '1W-BUS'
                    );
                }
            } else {
                ConfigMegaService::setPortType(
                    $deviceId,
                    $this->port_repository->getNumPortByID($port_id),
                    'IN'
                );

                Port::where('object', $termostat->id_object)
                    ->update(['object' => null, 'comment' => '', 'status' => 'IN']);
            }

            if (($data['room'] != null) && ($data['room'] != 0)) {
                RoomService::addTermostat($data['room'], $data['optimal']);
            }

            $this->prepare($termostat, $data);
            $termostat->save();
        });

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php check_termostat.php '.$termostat->id_object);

        return $termostat->id;
    }
}
