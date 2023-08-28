<?php

namespace Database\Seeders\Fakes;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Count;
use App\Models\HomeObject;

class FakeCountsTableSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $objects = HomeObject::limit(2)->get();

        $counts = [
            [
                'name' => 'Счетчик холодной воды',
                'type' => Count::TYPE_WATER,
                'id_object' => $objects[0]->id,
                'impulse' => 10.5,
                'unit' => 'л',
                'today_value' => 0.7,
                'total_value' => 65.5
            ],
            [
                'name' => 'Счетчик электричества',
                'type' => Count::TYPE_ELECTRO,
                'id_object' => $objects[1]->id,
                'impulse' => 64.3,
                'unit' => 'кв/ч',
                'today_value' => 60.5,
                'total_value' => 16.88
            ],
        ];

        Count::insert($counts);
    }
}
