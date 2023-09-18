<?php

namespace Tests\TestData;

use App\Models\Device;
use App\Models\DevType;
use App\Models\HomeObject;
use App\Models\Lock;
use App\Models\Port;

class LocksData
{
    /**
     * Генератор сущностей для замка
     */
    public function generateLock(): array
    {
        $devType = DevType::create([
            'name' => 'Monoblock 14IN/14OUT',
            'port_numbers' => 'in 0 6;out 7 13;dig 14 14;in 15 21;out 22 28;dig 29 44',
        ]);

        $device = Device::create([
            'ip_address' => '161.165.20.179',
            'description' => 'Тестовый контроллер',
            'type' => $devType->id,
            'active' => 1,
        ]);

        $port = Port::create([
            'id_device' => $device->id,
            'num_port' => 1,
            'type' => 'in',
            'status' => 'OUT',
            'comment' => 'Тестовый порт',
        ]);

        $object = HomeObject::create([
            'name' => 'Тестовый замок',
            'type' => 'lock',
            'status' => 'open',
        ]);

        $lock = Lock::create([
            'name' => 'Тестовый замок',
            'type' => Lock::TYPE_LATCH,
            'id_object' => $object->id,
            'place' => 'port',
            'port_open' => $port->id,
        ]);

        return [
            'dev_type' => $devType,
            'device' => $device,
            'port' => $port,
            'object' => $object,
            'lock' => $lock,
        ];
    }
}
