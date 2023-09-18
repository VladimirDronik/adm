<?php

namespace Tests\TestData;

use App\Models\HomeObject;
use App\Models\Virtual;

class VirtualsData
{
    /**
     * Генератор сущностей для виртуального устройства
     */
    public function generateVirtual(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовое виртуальное устройство',
            'type' => 'virtual',
            'status' => 'on',
        ]);

        $virtual = Virtual::create([
            'name' => 'Тестовое виртуальное устройство',
            'id_object' => $object->id,
        ]);

        return [
            'object' => $object,
            'virtual' => $virtual,
        ];
    }
}
