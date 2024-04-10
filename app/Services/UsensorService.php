<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 20.03.20
 * Time: 10:40
 */

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Port;
use App\Models\Usensor;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class UsensorService
{
    public function __construct(
        private UsensorObjectService $usensor_object_service,
        private PortRepository $portRepository,
        private PortService $portService
    ) {
    }

    public function prepare(Usensor $usensor, array $data)
    {
        unset($data['object_type']);
        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }
        $usensor->fill($data);
    }

    /**
     * Создание универсального датчка. Если $data['type'] === 'auto',
     * то еще создается объект с методом.
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $usensor = new Usensor();

        $port_SDA = $data['port_SDA'] ?? null;
        $port_SCL = $data['port_SCL'] ?? null;
        $deviceID = $data['device_id'];

        $this->prepare($usensor, $data);

        DB::transaction(function () use (&$usensor, $port_SDA, $port_SCL, $data, $deviceID) {
            $unique_name = HomeObject::getUniqueObjectName(0, $usensor->name);

            $object = $this->usensor_object_service
                ->createUsensorObject($unique_name);

            $this->usensor_object_service
                ->createUsensorObjectMethodsWithEvents($object->id);

            $usensor->id_object = $object->id;
            $usensor->save();

            if ($port_SDA) {
                Port::where('id', $port_SDA)->update([
                    'object' => $object->id,
                    'status' => 'I2C',
                    'comment' => $data['name'],
                ]);

                ConfigMegaService::setPortType(
                    $deviceID,
                    $this->portRepository->getNumPortByID($port_SDA),
                    'SDA'
                );
            }

            if ($port_SCL) {
                Port::where('id', $port_SCL)->update([
                    'object' => $object->id,
                    'status' => 'I2C',
                    'comment' => $data['name'],
                ]);

                ConfigMegaService::setPortType(
                    $deviceID,
                    $this->portRepository->getNumPortByID($port_SCL),
                    'SCL'
                );
            }
        });

        return $usensor->id;
    }

    /**
     * Удаление универсального датчика. Если связанный объект системный, то удаление объекта, метода, задачи планировщика,
     * созданных автоматически при создании датчика температуры
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $usensor = Usensor::findOrFail($id);

        //Убираем все данные на порту
        Port::where('object', $usensor->id_object)
            ->update([
                'object' => null,
                'status' => 'IN',
                'comment' => '',
            ]);

        if ($usensor->iobject && $usensor->iobject->is_system) {
            DB::transaction(function () use (&$usensor) {
                //if (!HomeObject::isObjectUsed($termostat->id_object, $termostat->id, 'termostats')) {
                HomeObject::deleteAutoObject($usensor->id_object);
                //}
                $usensor->delete();
            });
        } else {
            $usensor->delete();
        }

        return true;
    }

    private function isUpdateAutoObjectName(Usensor $usensor, string $name): bool
    {
        return $usensor->name !== trim($name) && $usensor->iobject && $usensor->iobject->is_system;
    }

    /**
     * Обновление Универсального датчика. Если изменилось название и у датчика системный объект, то
     * изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @throws \Throwable
     */
    public function update(Usensor $usensor, array $data): int
    {
        DB::transaction(function () use (&$usensor, $data) {
            if ($this->isUpdateAutoObjectName($usensor, $data['name'])) {
                $usensor->iobject->name = HomeObject::getUniqueObjectName(
                    $usensor->iobject->id,
                    trim($data['name'])
                );
                $usensor->iobject->save();
            }

            //Убираем все данные на порту
            Port::where('object', $usensor->id_object)
                ->update([
                    'object' => null,
                    'status' => 'IN',
                    'comment' => '',
                ]);

            ConfigMegaService::setPortType(
                $data['device_id'],
                $this->portRepository->getNumPortByID($data['port_SDA']),
                'IN'
            );

            ConfigMegaService::setPortType(
                $data['device_id'],
                $this->portRepository->getNumPortByID($data['port_SCL']),
                'IN'
            );

            if ($data['port_SDA']) {
                Port::where('id', $data['port_SDA'])
                    ->update([
                        'object' => $data['id_object'],
                        'status' => 'I2C',
                        'comment' => $data['name'],
                    ]);

                ConfigMegaService::setPortType(
                    $data['device_id'],
                    $this->portRepository->getNumPortByID($data['port_SDA']),
                    'SDA'
                );
            }

            if ($data['port_SCL']) {
                Port::where('id', $data['port_SCL'])
                    ->update([
                        'object' => $data['id_object'],
                        'status' => 'I2C',
                        'comment' => $data['name'],
                    ]);

                ConfigMegaService::setPortType(
                    $data['device_id'],
                    $this->portRepository->getNumPortByID($data['port_SCL']),
                    'SCL'
                );
            }
            $this->prepare($usensor, $data);
            $usensor->save();
        });

        return $usensor->id;
    }
}
