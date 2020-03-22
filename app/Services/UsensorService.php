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
use Illuminate\Support\Facades\DB;

class UsensorService
{

    private $usensor_object_service;

    public function __construct(UsensorObjectService $usensor_object_service)
    {
        $this->usensor_object_service = $usensor_object_service;
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
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $usensor = new Usensor();

        $port_SDA = $data['port_SDA'] ?? null;
        $port_SCL = $data['port_SCL'] ?? null;


        $this->prepare($usensor, $data);


        if ($data['object_type'] === 'manual') {

            $usensor->save();

        } elseif ($data['object_type'] === 'auto') {

            DB::transaction(function () use (&$usensor, $port_SDA, $port_SCL) {

                $unique_name = HomeObject::getUniqueObjectName(0, $usensor->name);
                $object = $this->usensor_object_service->createUsensorObject($unique_name);
                $this->usensor_object_service->createUsensorObjectMethodsWithEvents($object->id);
                $usensor->id_object = $object->id;
                $usensor->save();

                if ($port_SDA) {
                    Port::where('id', $port_SDA)->update(['object' => $object->id]);
                }

                if ($port_SCL) {
                    Port::where('id', $port_SCL)->update(['object' => $object->id]);
                }

            });

        }

        return $usensor->id;

    }


    /**
     * Удаление универсального датчика. Если связанный объект системный, то удаление объекта, метода, события,
     * созданных автоматически при создании термостата
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $usensor = Usensor::findOrFail($id);

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
     * @param Usensor $usensor
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Usensor $usensor, array $data): int
    {
        DB::transaction(function () use (&$usensor, $data) {
            if ($this->isUpdateAutoObjectName($usensor, $data['name'])) {
                $usensor->iobject->name = HomeObject::getUniqueObjectName($usensor->iobject->id, trim($data['name']));
                $usensor->iobject->save();
            }
            $this->prepare($usensor, $data);
            $usensor->save();
        });

        return $usensor->id;
    }
}