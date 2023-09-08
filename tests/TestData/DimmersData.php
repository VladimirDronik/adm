<?php

namespace Tests\TestData;

use App\Models\Dimmer;
use App\Models\HomeObject;

class DimmersData
{
    /**
     * Генератор сущностей для диммера
     *
     * @return array
     */
    public function generateDimmer(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовый диммер',
            'type' => 'dimmer',
            'status' => 'on',
        ]);

        $dimmer = Dimmer::create([
            'name' => 'Тестовый диммер',
            'value' => 10,
            'speed' => 10,
            'id_object' => $object->id,
        ]);

        return [
            'object' => $object,
            'dimmer' => $dimmer,
        ];
    }
}
