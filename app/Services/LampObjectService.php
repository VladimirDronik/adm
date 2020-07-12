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

        $object->type = $type === Lamp::TYPE_LAMP;
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
    public function createMethodOff(int $object_id)
    {
        Method::forceCreate([
            'name' => 'Выключить лампу',
            'id_object' => $object_id,
            'script' => null,
            'comment' => 'Выключить лампу',
            'is_system' => 1
        ]);
    }

    /**
     * Создание метода 'Включить лампу'
     *
     * @param int $object_id
     */
    public function createMethodOn(int $object_id)
    {
        Method::forceCreate([
            'name' => 'Включить лампу',
            'id_object' => $object_id,
            'script' => null,
            'comment' => 'Включить лампу',
            'is_system' => 1
        ]);
    }

    /**
     * Автосоздание методов для объекта, который был
     * создан автоматически для лампы
     *
     * @param int $object_id
     * @return void
     */
    public function createLampObjectMethods(int $object_id)
    {
        $this->createMethodOn($object_id);
        $this->createMethodOff($object_id);
    }
}