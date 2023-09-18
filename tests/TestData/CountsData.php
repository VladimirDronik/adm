<?php

namespace Tests\TestData;

use App\Models\Count;
use App\Models\HomeObject;

class CountsData
{
    /**
     * Генератор сущностей для счетчика
     */
    public function generateCount(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовый счетчик',
            'type' => 'count',
            'status' => 'on',
        ]);

        $count = Count::create([
            'name' => 'Тестовый счетчик',
            'type' => Count::TYPE_ELECTRO,
            'id_object' => $object->id,
            'impulse' => 1,
            'unit' => 'КВт/ч',
            'today_value' => 10,
            'total_value' => 20,
        ]);

        return [
            'object' => $object,
            'count' => $count,
        ];
    }
}
