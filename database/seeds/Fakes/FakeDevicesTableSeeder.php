<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\DevType;
use App\Services\DeviceService;
use App\Models\Device;

class FakeDevicesTableSeeder extends Seeder
{
    const COUNT = 10;

    private $faker;
    private $devtypes;
    private $device_service;

    public function __construct(DeviceService $device_service)
    {
        $this->faker = Factory::create();
        $this->devtypes = DevType::all();

        if (!count($this->devtypes)) {
            throw new Exception('Таблица Devtypes пустая. Генерация контроллеров невозможна');
        }

        $this->device_service = $device_service;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < self::COUNT; $i++) {

            try {
                $this->createDevice($i);
            } catch (\Throwable $e) {

            }
        }

        Device::inRandomOrder()->limit((int)(self::COUNT/3))->update(['active' => 1]);
    }

    public function createDevice($index)
    {
        $data = [
            'type' => $this->getRandTypeId(),
            'description' => 'Контроллер '.(1 + $index),
            'ip_address' => $this->faker->ipv4
        ];

        $this->device_service->store($data);
    }

    public function getRandTypeId()
    {
        $count = count($this->devtypes);

        return $this->devtypes[rand(0, $count-1)]->id;
    }
}
