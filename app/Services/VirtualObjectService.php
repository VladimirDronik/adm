<?php

namespace App\Services;

use App\Models\Method;
use App\Models\ObjType;
use App\Models\HomeObject;

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
    public function createMethodOff(int $objectId): Method
    {
        return Method::forceCreate([
            'name' => 'Выключить виртуальное устройство',
            'id_object' => $objectId,
            'script' => null,
            'easy' => null,
            'comment' => 'Выключить виртуальное устройство',
            'is_system' => 0,
        ]);
    }

    /**
     * Создание метода 'Включить виртуальное устройство'
     */
    public function createMethodOn(int $objectId): Method
    {
        return Method::forceCreate([
            'name' => 'Включить виртуальное устройство',
            'id_object' => $objectId,
            'script' => null,
            'easy' => null,
            'comment' => 'Включить виртуальное устройство',
            'is_system' => 0,
        ]);
    }

    /**
     * Создание метода 'Переключить виртуальное устройство'
     */
    public function createMethodOnOff(int $objectId)
    {
        Method::forceCreate([
            'name' => 'Переключить виртуальное устройство',
            'id_object' => $objectId,
            'script' => null,
            'easy' => null,
            'comment' => 'Переключить виртуальное устройство',
            'is_system' => 0,
        ]);
    }

    private function updateMethodOn(int $objectId, $deviceId, $portId)
    {
        if ($deviceId && ! is_null($portId)) {
            $easyString = $deviceId.';'.$portId.':1';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::where('id_object', $objectId)
            ->where('name', 'Включить реле')
            ->update(['easy' => $easyString]);
    }

    private function updateMethodOff(int $objectId, $deviceId, $portId)
    {

        if ($deviceId && ! is_null($portId)) {
            $easyString = $deviceId.';'.$portId.':0';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::where('id_object', $objectId)
            ->where('name', 'Выключить реле')
            ->update(['easy' => $easyString]);
    }

    private function updateMethodOnOff(int $objectId, $deviceId, $portId)
    {
        if ($deviceId && ! is_null($portId)) {
            $easyString = $deviceId.';'.$portId.':2';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::where('id_object', $objectId)
            ->where('name', 'Переключить реле')
            ->update(['easy' => $easyString]);
    }

    /**
     * Автосоздание методов для объекта, который был
     * создан автоматически для реле
     */
    public function createVirtualObjectMethods(int $objectId): array
    {
        $methodOn = $this->createMethodOn($objectId);
        $methodOff = $this->createMethodOff($objectId);
        $this->createMethodOnOff($objectId);

        $methods = [
            'method_on' => $methodOn->id,
            'method_off' => $methodOff->id,
        ];

        return $methods;
    }

    public function updateRelayObjectMethods(int $objectId, $deviceId, $portId)
    {
        $this->updateMethodOff($objectId, $deviceId, $portId);
        $this->updateMethodOn($objectId, $deviceId, $portId);
        $this->updateMethodOnOff($objectId, $deviceId, $portId);
    }
}
