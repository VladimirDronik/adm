<?php

namespace Database\Seeders\Tests;

use App\Models\HomeObject;
use App\Models\Hygrostat;
use Illuminate\Database\Seeder;

class HygrostatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $object = HomeObject::create([
            'name' => 'Тестовый Гигростат',
            'type' => 'hygrostat',
            'status' => 'on',
        ]);

        Hygrostat::create([
            'name' => 'Тестовый Гигростат',
            'id_object' => $object->id,
            'optimal' => 25,
            'gisteresis' => 1,
            'type' => 1,
            'min_threshold' => 10,
            'max_threshold' => 20,
            'min_alarm' => 5,
            'max_alarm' => 30,
        ]);
    }
}
