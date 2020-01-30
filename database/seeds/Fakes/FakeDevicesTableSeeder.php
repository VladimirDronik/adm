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

    /**
     * FakeDevicesTableSeeder constructor.
     * @param DeviceService $device_service
     * @throws Exception
     */
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
     * @throws Throwable
     */
    public function run()
    {
        for ($i = 0; $i < self::COUNT; $i++) {
            $this->createDevice($i);
        }

        Device::inRandomOrder()->limit((int)(self::COUNT/3))->update(['active' => 1]);
    }

    /**
     * @param $index
     * @throws Throwable
     */
    public function createDevice($index)
    {
        $data = [
            'type' => $this->getRandTypeId(),
            'description' => 'Контроллер '.(1 + $index),
            'ip_address' => $this->faker->ipv4
        ];

        $this->device_service->store($data, false);
    }

    public function getRandTypeId()
    {
        return $this->devtypes[rand(0, count($this->devtypes)-1)]->id;
    }
}
