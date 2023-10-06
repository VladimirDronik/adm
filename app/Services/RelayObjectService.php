<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\Relay;

class RelayObjectService
{
    /**
     * Автосоздание объекта для реле
     */
    public function createRelayObject(string $name, string $type): HomeObject
    {
        $object = new HomeObject();

        $object->type = $type === Relay::TYPE_SOCKET ? ObjType::TYPE_SOCKET : ObjType::TYPE_RELAY;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    /**
     * Создание метода 'Выключить реле'
     */
    public function createMethodOff(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':0';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::forceCreate([
            'name' => 'Выключить реле',
            'id_object' => $object_id,
            'script' => null,
            'easy' => $easyString,
            'comment' => 'Выключить реле',
            'is_system' => 1,
        ]);
    }

    /**
     * Создание метода 'Включить реле'
     */
    public function createMethodOn(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':1';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::forceCreate([
            'name' => 'Включить реле',
            'id_object' => $object_id,
            'script' => null,
            'easy' => $easyString,
            'comment' => 'Включить реле',
            'is_system' => 1,
        ]);
    }

    /**
     * Создание метода 'Включить реле'
     */
    public function createMethodOnOff(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':2';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::forceCreate([
            'name' => 'Переключить реле',
            'id_object' => $object_id,
            'script' => null,
            'easy' => $easyString,
            'comment' => 'Переключить реле',
            'is_system' => 1,
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
        Method::where('easy', $easyString)
            ->update(['easy' => null]);

        Method::where('id_object', $object_id)
            ->where('name', 'Включить реле')
            ->update(['easy' => $easyString]);
    }

    private function updateMethodOff(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':0';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)
            ->update(['easy' => null]);

        Method::where('id_object', $object_id)
            ->where('name', 'Выключить реле')
            ->update(['easy' => $easyString]);
    }

    private function updateMethodOnOff(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':2';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)
            ->update(['easy' => null]);

        Method::where('id_object', $object_id)
            ->where('name', 'Переключить реле')
            ->update(['easy' => $easyString]);
    }

    /**
     * Автосоздание методов для объекта, который был
     * создан автоматически для реле
     *
     * @return void
     */
    public function createRelayObjectMethods(int $object_id, $device_id, $port_id)
    {
        $this->createMethodOn($object_id, $device_id, $port_id);
        $this->createMethodOff($object_id, $device_id, $port_id);
        $this->createMethodOnOff($object_id, $device_id, $port_id);
    }

    public function updateRelayObjectMethods(int $object_id, $device_id, $port_id)
    {
        $this->updateMethodOff($object_id, $device_id, $port_id);
        $this->updateMethodOn($object_id, $device_id, $port_id);
        $this->updateMethodOnOff($object_id, $device_id, $port_id);
    }
}
