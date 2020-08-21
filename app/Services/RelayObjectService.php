<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\Relay;

class RelayObjectService {

    /**
     * Автосоздание объекта для реле
     *
     * @param string $name
     * @return HomeObject
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
     *
     * @param int $object_id
     */
    public function createMethodOff(int $object_id)
    {
        Method::forceCreate([
            'name' => 'Выключить реле',
            'id_object' => $object_id,
            'script' => null,
            'comment' => 'Выключить реле',
            'is_system' => 1
        ]);
    }

    /**
     * Создание метода 'Включить реле'
     *
     * @param int $object_id
     */
    public function createMethodOn(int $object_id)
    {
         Method::forceCreate([
            'name' => 'Включить реле',
            'id_object' => $object_id,
            'script' => null,
            'comment' => 'Включить реле',
            'is_system' => 1
        ]);
    }

    /**
     * Автосоздание методов для объекта, который был
     * создан автоматически для реле
     *
     * @param int $object_id
     * @return void
     */
    public function createRelayObjectMethods(int $object_id)
    {
        $this->createMethodOn($object_id);
        $this->createMethodOff($object_id);
    }
}