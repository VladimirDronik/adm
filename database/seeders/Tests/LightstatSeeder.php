<?php

namespace Database\Seeders\Tests;

use App\Models\HomeObject;
use App\Models\Lightstat;
use Illuminate\Database\Seeder;

class LightstatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $object = HomeObject::create([
            'name' => 'Тестовый Светостат',
            'type' => 'lightstat',
            'status' => 'on',
        ]);

        Lightstat::create([
            'name' => 'Тестовый Светостат',
            'id_object' => $object->id,
            'current' => 10,
            'optimal' => 25,
            'gisteresis' => 1,
            'mode' => 1,
            'min_threshold' => 10,
            'max_threshold' => 20,
            'min_alarm' => 5,
            'max_alarm' => 30,
        ]);
    }
}
