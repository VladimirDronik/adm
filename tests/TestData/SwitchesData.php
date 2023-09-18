<?php

namespace Tests\TestData;

use App\Models\DeviceSwitch;
use App\Models\HomeObject;

class SwitchesData
{
    /**
     * Генератор сущностей для выключателя
     */
    public function generateSwitch(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовый выключатель',
            'type' => 'switch',
            'status' => 'on',
        ]);

        $switch = DeviceSwitch::create([
            'name' => 'Тестовый выключатель',
            'type' => DeviceSwitch::TYPE_SWITCH,
            'id_object' => $object->id,
        ]);

        return [
            'object' => $object,
            'switch' => $switch,
        ];
    }
}
