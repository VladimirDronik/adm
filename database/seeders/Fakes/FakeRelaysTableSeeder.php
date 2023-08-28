<?php

namespace Database\Seeders\Fakes;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Relay;
use App\Models\HomeObject;

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
                'type' => Relay::TYPE_SOCKET,
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
