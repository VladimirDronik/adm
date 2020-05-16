<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 14.05.20
 * Time: 20:05
 */

namespace App\Services;
use App\Models\HomeObject;
use App\Models\ObjType;
use ScriptsTableSeeder;


class MotionSensorObjectService
{

    /**
     * Автосоздание объекта для сухого контакта
     *
     * @param string $name
     * @return HomeObject
     */
    public function createMotionsensorObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_MOTIONSENSOR;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }



}