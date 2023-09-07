<?php

namespace Tests\TestData;

use App\Models\Device;
use App\Models\DevType;

class DevicesData
{
    /**
     * Генератор сущностей для контроллера
     *
     * @return array
     */
    public function generateDevice(): array
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

        return [
            'dev_type' => $devType,
            'device' => $device,
        ];
    }
}
