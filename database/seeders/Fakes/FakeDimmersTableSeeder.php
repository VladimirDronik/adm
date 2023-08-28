<?php

namespace Database\Seeders\Fakes;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Dimmer;
use App\Models\HomeObject;

class FakeDimmersTableSeeder extends Seeder
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

        $dimmers = [
            [
                'name' => 'Диммер в спальне',
                'id_object' => $objects[0]->id,
                'value' => 100,
                'speed' => 2
            ],
            [
                'name' => 'Диммер на кухне',
                'id_object' => $objects[1]->id,
                'value' => 50,
                'speed' => 5
            ],
        ];

        Dimmer::insert($dimmers);
    }
}
