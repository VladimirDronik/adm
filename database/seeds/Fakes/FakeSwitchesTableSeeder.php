<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\DeviceSwitch;
use App\Models\HomeObject;

class FakeSwitchesTableSeeder extends Seeder
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
                'name' => 'Выключатель 1',
                'type' => DeviceSwitch::TYPE_SWITCH,
                'id_object' => $objects[0]->id,
            ],
            [
                'name' => 'Выключатель 2',
                'type' => DeviceSwitch::TYPE_BUTTON,
                'id_object' => $objects[1]->id,
            ],
        ];

        DeviceSwitch::insert($relays);
    }
}
