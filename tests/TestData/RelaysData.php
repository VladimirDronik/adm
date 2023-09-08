<?php

namespace Tests\TestData;

use App\Models\HomeObject;
use App\Models\Relay;

class RelaysData
{
    /**
     * Генератор сущностей для реле
     *
     * @return array
     */
    public function generateRelay(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовое реле',
            'type' => 'relay',
            'status' => 'on',
        ]);

        $relay = Relay::create([
            'name' => 'Тестовое реле',
            'type' => Relay::TYPE_RELAY,
            'id_object' => $object->id,
        ]);

        return [
            'object' => $object,
            'relay' => $relay,
        ];
    }
}
