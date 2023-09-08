<?php

namespace Tests\TestData;

use App\Models\HomeObject;
use App\Models\Lamp;

class LampsData
{
    /**
     * Генератор сущностей для лампы
     *
     * @return array
     */
    public function generateLamp(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовая лампа',
            'type' => 'lamp',
            'status' => 'on',
        ]);

        $lamp = Lamp::create([
            'name' => 'Тестовая лампа',
            'type' => Lamp::TYPE_LAMP,
            'id_object' => $object->id,
        ]);

        return [
            'object' => $object,
            'lamp' => $lamp,
        ];
    }
}
