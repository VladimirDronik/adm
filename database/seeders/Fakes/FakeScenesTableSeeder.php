<?php

namespace Database\Seeders\Fakes;

use App\Models\Scene;
use Faker\Factory;
use Illuminate\Database\Seeder;

class FakeScenesTableSeeder extends Seeder
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
        $scenes = [
            [
                'label' => 'Дом 1-й этаж',
                'image' => 'scene1.jpg',
                'background_color' => '#fffff',
                'sort' => 1,
                'active' => 1,
            ],
            [
                'label' => 'Дом 2-й этаж',
                'image' => 'scene2_1400.jpg',
                'background_color' => '#00ffff',
                'sort' => 2,
                'active' => 0,
            ],
            [
                'label' => 'Подвал',
                'image' => 'scene1.jpg',
                'background_color' => '',
                'sort' => 3,
                'active' => 0,
            ],
        ];

        Scene::insert($scenes);
    }
}
