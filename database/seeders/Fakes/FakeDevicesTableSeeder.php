<?php

namespace Database\Seeders\Fakes;

use App\Models\Device;
use App\Models\DevType;
use App\Services\DeviceService;
use Exception;
use Faker\Factory;
use Illuminate\Database\Seeder;

class FakeDevicesTableSeeder extends Seeder
{
    const COUNT = 10;

    private $faker;

    private $devtypes;

    private $device_service;

    /**
     * FakeDevicesTableSeeder constructor.
     *
     * @throws Exception
     */
    public function __construct(DeviceService $device_service)
    {
        $this->faker = Factory::create();
        $this->devtypes = DevType::all();

        if (! count($this->devtypes)) {
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

        Device::inRandomOrder()->limit((int) (self::COUNT / 3))->update(['active' => 1]);
    }

    /**
     * @throws Throwable
     */
    public function createDevice($index)
    {
        $data = [
            'type' => $this->getRandTypeId(),
            'description' => 'Контроллер '.(1 + $index),
            'ip_address' => $this->faker->ipv4,
        ];

        $this->device_service->store($data, true, false);
    }

    public function getRandTypeId()
    {
        return $this->devtypes[rand(0, count($this->devtypes) - 1)]->name;
    }
}
