<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 13.04.21
 * Time: 14:58
 */

namespace App\Services;
use App\Models\HomeObject;
use App\Models\ObjType;

class BoilerObjectService
{


    /**
     * Автосоздание объекта для диммера
     *
     * @param string $name
     * @return HomeObject
     */
    public function createBoilerObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_BOILER;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }
}