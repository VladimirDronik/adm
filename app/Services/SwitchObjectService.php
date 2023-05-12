<?php

namespace App\Services;

use App\Models\DeviceSwitch;
use App\Models\HomeObject;
use App\Models\ObjType;

class SwitchObjectService {

    /**
     * Автосоздание объекта для выключателя
     *
     * @param string $name
     * @return HomeObject
     */
    public function createSwitchObject(string $name, string $type): HomeObject
    {
        $object = new HomeObject();

        $object->type = $type === DeviceSwitch::TYPE_BUTTON ? ObjType::TYPE_BUTTON : ObjType::TYPE_SWITCH;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    /**
     * Изменение типа объекта
     *
     * @param HomeObject $object
     * @param string $type
     * @return HomeObject
     */
    public function updateSwitchObjectType(HomeObject $object, string $type): HomeObject
    {
        $object->type = $type === DeviceSwitch::TYPE_BUTTON ? ObjType::TYPE_BUTTON : ObjType::TYPE_SWITCH;

        $object->save();

        return $object;
    }
}