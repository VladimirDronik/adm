<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Port;
use App\Models\Virtual;
use Illuminate\Support\Facades\DB;

class VirtualService
{
    private $virtual_object_service;

    public function __construct(VirtualObjectService $virtual_object_service)
    {
        $this->virtual_object_service = $virtual_object_service;
    }

    /**
     * Удаление реле. Если связанный объект системный, то удаление объекта и методов,
     * созданных автоматически при создании реле
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $virtual = Virtual::findOrFail($id);

        if ($virtual->object && $virtual->object->is_system) {
            DB::transaction(function () use (&$virtual) {
                //if (!HomeObject::isObjectUsed($relay->id_object, $relay->id, 'relays')) {
                HomeObject::deleteAutoObject($virtual->id_object);
                //}
                $virtual->delete();
            });
        } else {
            $virtual->delete();
        }

        Port::where('object', $virtual->id_object)->update(['object' => null, 'method' => null]);

        return true;
    }

    public function prepareVirtual(Virtual $virtual, array $data)
    {
        $virtual->name = trim($data['name']);
    }

    /**
     * Создание виртуального устройства.
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {

        $virtual = new Virtual();
        $this->preparevirtual($virtual, $data);

        DB::transaction(function () use (&$virtual) {
            $unique_name = HomeObject::getUniqueObjectName(0, $virtual->name);
            $object = $this->virtual_object_service->createVirtualObject($unique_name);
            $methods = $this->virtual_object_service->createVirtualObjectMethods($object->id);
            $virtual->id_object = $object->id;
            $virtual->method_on = $methods['method_on'];
            $virtual->method_off = $methods['method_off'];
            $virtual->save();

        });

        return $virtual->id;
    }

    private function isUpdateAutoObjectName(Virtual $virtual, string $name): bool
    {
        return $virtual->name !== trim($name) && $virtual->object && $virtual->object->is_system;
    }

    /**
     * Обновление виртульного устройства.
     *
     * @throws \Throwable
     */
    public function update(Virtual $virtual, array $data): int
    {

        DB::transaction(function () use (&$virtual, $data) {
            if ($this->isUpdateAutoObjectName($virtual, $data['name'])) {
                $virtual->object->name = HomeObject::getUniqueObjectName($virtual->object->id, trim($data['name']));
                $virtual->object->save();

            }
            $this->prepareVirtual($virtual, $data);
            $virtual->save();

            //Сохраняем данные в таблицу Алисы или включаем запись если она есть уже
            if (isset($data['alice_checkbox'])) {
                AliceDevicesService::addOrReplaceDevice($virtual->object->id, $data['alice_command'], $data['room']);
            } else {
                AliceDevicesService::setActive($virtual->object->id, 0);
            }

        });

        return $virtual->id;
    }
}
