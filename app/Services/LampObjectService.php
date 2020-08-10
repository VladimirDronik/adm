<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 11.07.20
 * Time: 10:46
 */

namespace App\Services;

use App\Models\Lamp;
use App\Models\HomeObject;
use App\Models\Method;


class LampObjectService
{

    /**
     * Автосоздание объекта для лампы
     *
     * @param string $name
     * @return HomeObject
     */
    public function createLampObject(string $name, string $type): HomeObject
    {
        $object = new HomeObject();

        $object->type = Lamp::TYPE_LAMP;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }


    /**
     * Создание метода 'Выключить лампу'
     *
     * @param int $object_id
     */
    private function createMethodOff(int $object_id,  $device_id,  $port_id)
    {
        if($device_id && $port_id)
        $easyString = $device_id.';'.$port_id.':0';
            else $easyString = null;


        Method::forceCreate([
            'name' => 'Выключить лампу',
            'id_object' => $object_id,
            'script' => null,
            'easy' => $easyString,
            'comment' => 'Выключить лампу',
            'is_system' => 1
        ]);
    }

    /**
     * Создание метода 'Включить лампу'
     *
     * @param int $object_id
     */
    private function createMethodOn(int $object_id,  $device_id,  $port_id)
    {
        if($device_id && $port_id)
            $easyString = $device_id.';'.$port_id.':1';
        else $easyString = null;

        Method::forceCreate([
            'name' => 'Включить лампу',
            'id_object' => $object_id,
            'script' => null,
            'easy' => $easyString,
            'comment' => 'Включить лампу',
            'is_system' => 1
        ]);
    }

    /**
     * Создание метода 'Смена состояния лампы'
     *
     * @param int $object_id
     */
    private function createMethodOnOff(int $object_id,  $device_id,  $port_id)
    {
        if($device_id && $port_id)
            $easyString = $device_id.';'.$port_id.':2';
        else $easyString = null;

        Method::forceCreate([
            'name' => 'Смена состояния лампы',
            'id_object' => $object_id,
            'script' => null,
            'easy' => $easyString,
            'comment' => 'Смена состояния лампы',
            'is_system' => 1
        ]);
    }

    private function updateMethodOff(int $object_id,  $device_id,  $port_id)
    {
        if($device_id && $port_id)
            $easyString = $device_id.';'.$port_id.':0';
        else $easyString = null;

        Method::where('id_object', $object_id)->where('name', 'Выключить лампу')->
        update(['easy' => $easyString]);
    }

    private function updateMethodOn(int $object_id,  $device_id,  $port_id)
    {
        if($device_id && $port_id)
            $easyString = $device_id.';'.$port_id.':1';
        else $easyString = null;

        Method::where('id_object', $object_id)->where('name', 'Включить лампу')->
        update(['easy' => $easyString]);
    }

    private function updateMethodOnOff(int $object_id,  $device_id,  $port_id)
    {
        if($device_id && $port_id)
            $easyString = $device_id.';'.$port_id.':2';
        else $easyString = null;

        Method::where('id_object', $object_id)->where('name', 'Смена состояния лампы')->
        update(['easy' => $easyString]);
    }

    /**
     * Автосоздание методов для объекта, который был
     * создан автоматически для лампы
     *
     * @param int $object_id
     * @param int $device_id
     * @param int $port_id
     * @return void
     */
    public function createLampObjectMethods(int $object_id,  $device_id,  $port_id)
    {
        $this->createMethodOn($object_id, $device_id, $port_id);
        $this->createMethodOff($object_id, $device_id, $port_id);
        $this->createMethodOnOff($object_id, $device_id, $port_id);
    }

    public function updateLampObjectMethods(int $object_id,  $device_id,  $port_id)
    {
        $this->updateMethodOff($object_id,  $device_id,  $port_id);
        $this->updateMethodOn($object_id,  $device_id,  $port_id);
        $this->updateMethodOnOff($object_id,  $device_id,  $port_id);

    }
}