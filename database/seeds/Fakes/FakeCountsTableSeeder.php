<?php

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
                'impulse' => 10,
                'unit' => 'm3',
                'today_value' => 0,
                'total_value' => 6500
            ],
            [
                'name' => 'Счетчик холодной воды',
                'type' => Count::TYPE_ELECTRO,
                'id_object' => $objects[1]->id,
                'impulse' => 6400,
                'unit' => 'kw/h',
                'today_value' => 600,
                'total_value' => 16500
            ],
        ];

        Count::insert($counts);
    }
}
