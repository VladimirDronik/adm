<?php

namespace Database\Seeders\Fakes;

use App\Models\HomeObject;
use App\Models\Relay;
use Faker\Factory;
use Illuminate\Database\Seeder;

class FakeRelaysTableSeeder extends Seeder
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

        $relays = [
            [
                'name' => 'Реле 1',
                'type' => Relay::TYPE_RELAY,
                'id_object' => $objects[0]->id,
            ],
            [
                'name' => 'Реле 2',
                'type' => Relay::TYPE_RELAY,
                'id_object' => $objects[1]->id,
            ],
        ];

        Relay::insert($relays);
    }
}
