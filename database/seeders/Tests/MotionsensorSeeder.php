<?php

namespace Database\Seeders\Tests;

use App\Models\HomeObject;
use App\Models\Motionsensor;
use Illuminate\Database\Seeder;

class MotionsensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $object = HomeObject::create([
            'name' => 'Тестовый Датчик движения',
            'type' => 'motionsensor',
            'status' => 'on',
        ]);

        Motionsensor::create([
            'name' => 'Тестовый Датчик движения',
            'id_object' => $object->id,
        ]);
    }
}
