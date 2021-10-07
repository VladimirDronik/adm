<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Hygrostat;
use App\Models\Port;
use App\Models\Termostat;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;
use App\Services\PortService;

class HygrostatService {

    private $hygrostat_object_service;
    private $port_service;
    private $port_repository;

    public function __construct(HygrostatObjectService $hygrostat_object_service,
                                PortService $portService, PortRepository $portRepository)
    {
        $this->hygrostat_object_service = $hygrostat_object_service;
        $this->port_service = $portService;
        $this->port_repository = $portRepository;
    }

    /**
     * Удаление термостата. Если связанный объект системный, то удаление объекта, метода, задачи расписания,
     * созданных автоматически при создании термостата
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $termostat = Termostat::findOrFail($id);


        $deviceAndPort = $this->port_service->getIdDeviceAndPortId($termostat->id_object);

        ConfigMegaService::setPortType($deviceAndPort['id_device'], $this->port_repository->getNumPortByID($deviceAndPort['id_port']), 'IN');

        //В портах удаляем все упоминания о термостате, порт переводим в режим IN
        Port::where('object', $termostat->id_object)->update(['status' => 'IN', 'object' => NULL,
            'comment' => '']);

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

    public function prepare(Hygrostat $hygrostat, array $data)
    {

        unset($data['object_type']);
        unset($data['device_id']);
        unset($data['placetype_radio']);
        unset($data['HPController_id']);

        $data['min_threshold'] = '0';
        $data['max_threshold'] = '100';
        $data['current'] = '0';

        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }

        $hygrostat->fill($data);
    }

    /**
     * Создание гигростата. Если $data['type'] === 'auto',
     * то еще создается объект с методом и событием.
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {

        $hygrostat = new Hygrostat();


        $placeType = $data['placetype'];

        $this->prepare($hygrostat, $data);
        $hygrostat->current = null;

        if ($data['object_type'] === 'manual') {
            $hygrostat->save();
        } elseif ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$hygrostat,  $placeType) {
                $unique_name = HomeObject::getUniqueObjectName(0, $hygrostat->name);
                $object = $this->hygrostat_object_service->createHygrostatObject($unique_name);
                $this->hygrostat_object_service->createHygrostatObjectMethodsWithEvents($object->id);

                if($hygrostat->room != null)
                RoomService::addHygrostat($hygrostat->room, $hygrostat->optimal);

                $hygrostat->id_object = $object->id;
                $hygrostat->save();

            });
        }

        return $hygrostat->id;
    }

    private function isUpdateAutoObjectName(Hygrostat $hygrostat, string $name): bool
    {
        return $hygrostat->name !== trim($name) && $hygrostat->iobject && $hygrostat->iobject->is_system;
    }

    /**
     * Обновление термостата. Если изменилось название и у термостата системный объект, то
     * изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @param Termostat $termostat
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Termostat $termostat, array $data): int
    {

        $port_id = $data['port_id'] ?? null;
        $deviceId = $data['device_id'];
        $placeType = $data['placetype'];

        DB::transaction(function () use (&$termostat, $data, $port_id, $deviceId, $placeType) {
            if ($this->isUpdateAutoObjectName($termostat, $data['name'])) {
                $termostat->iobject->name = HomeObject::getUniqueObjectName($termostat->iobject->id, trim($data['name']));
                $termostat->iobject->save();

            }


            //Если порт указан, меняем его в портах для этого объекта, если нет, то убираем старый в портах
            if ($data['port_id'] && (($placeType == 'port') || ($placeType == '1wbus'))) {

                //Обнуляем порт, приводим в исходное состояние
                Port::where('object', $termostat->id_object)->update(['object' => NULL, 'comment' => '', 'status' => 'IN']);
               // ConfigMegaService::setPortType($deviceId, $this->port_repository->getNumPortByID($port_id), 'IN');

                //Привязываем объект к порту в БД
                Port::where('id',  $port_id)->update(['object' => $termostat->id_object,
                                                                        'comment' => $termostat->name]);

                //Переназначаем порт на контроллере
                if($placeType == 'port'){
                    Port::where('id',  $port_id)->update(['status' => '1WIRE']);
                    ConfigMegaService::setPortType($deviceId, $this->port_repository->getNumPortByID($port_id), '1WIRE');

                }
                elseif ($placeType == '1wbus') {
                    Port::where('id',  $port_id)->update(['status' => '1W-BUS']);
                    ConfigMegaService::setPortType($deviceId, $this->port_repository->getNumPortByID($port_id), '1W-BUS');

                }

            }else {
                ConfigMegaService::setPortType($deviceId, $this->port_repository->getNumPortByID($port_id), 'IN');

                Port::where('object', $termostat->id_object)->update(['object' => NULL, 'comment' => '', 'status' => 'IN']);
            }

            if(($data['room'] != null) && ($data['room'] != 0))
               RoomService::addTermostat($data['room'], $data['optimal']);

            $this->prepare($termostat, $data);
            $termostat->save();
        });

        return $termostat->id;
    }

}