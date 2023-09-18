<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;

class VirtualObjectService
{
    /**
     * Автосоздание объекта для реле
     */
    public function createVirtualObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_VIRTUAL;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    /**
     * Создание метода 'Выключить виртуальное устройство'
     */
    public function createMethodOff(int $object_id): Method
    {
        return Method::forceCreate([
            'name' => 'Выключить виртуальное устройство',
            'id_object' => $object_id,
            'script' => null,
            'easy' => null,
            'comment' => 'Выключить виртуальное устройство',
            'is_system' => 0,
        ]);
    }

    /**
     * Создание метода 'Включить виртуальное устройство'
     */
    public function createMethodOn(int $object_id): Method
    {
        return Method::forceCreate([
            'name' => 'Включить виртуальное устройство',
            'id_object' => $object_id,
            'script' => null,
            'easy' => null,
            'comment' => 'Включить виртуальное устройство',
            'is_system' => 0,
        ]);
    }

    /**
     * Создание метода 'Переключить виртуальное устройство'
     */
    public function createMethodOnOff(int $object_id)
    {
        Method::forceCreate([
            'name' => 'Переключить виртуальное устройство',
            'id_object' => $object_id,
            'script' => null,
            'easy' => null,
            'comment' => 'Переключить виртуальное устройство',
            'is_system' => 0,
        ]);
    }

    private function updateMethodOn(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':1';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::where('id_object', $object_id)->where('name', 'Включить реле')->
        update(['easy' => $easyString]);
    }

    private function updateMethodOff(int $object_id, $device_id, $port_id)
    {

        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':0';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::where('id_object', $object_id)->where('name', 'Выключить реле')->
        update(['easy' => $easyString]);
    }

    private function updateMethodOnOff(int $object_id, $device_id, $port_id)
    {

        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':2';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::where('id_object', $object_id)->where('name', 'Переключить реле')->
        update(['easy' => $easyString]);
    }

    /**
     * Автосоздание методов для объекта, который был
     * создан автоматически для реле
     */
    public function createVirtualObjectMethods(int $object_id): array
    {
        $methodOn = $this->createMethodOn($object_id);
        $methodOff = $this->createMethodOff($object_id);
        $this->createMethodOnOff($object_id);

        $methods = [
            'method_on' => $methodOn->id,
            'method_off' => $methodOff->id,
        ];

        return $methods;
    }

    public function updateRelayObjectMethods(int $object_id, $device_id, $port_id)
    {

        $this->updateMethodOff($object_id, $device_id, $port_id);
        $this->updateMethodOn($object_id, $device_id, $port_id);
        $this->updateMethodOnOff($object_id, $device_id, $port_id);

    }
}
