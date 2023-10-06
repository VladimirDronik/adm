<?php

namespace Tests\TestData;

use App\Models\Curtain;
use App\Models\Device;
use App\Models\DevType;
use App\Models\HomeObject;

class CurtainsData
{
    /**
     * Генератор сущностей для шторы
     */
    public function generateCurtain(): array
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

        $object = HomeObject::create([
            'name' => 'Тестовая штора',
            'type' => 'curtain',
            'status' => 'close',
        ]);

        $curtain = Curtain::create([
            'name' => 'Тестовая штора',
            'type' => Curtain::TYPE_CURTAIN,
            'id_object' => $object->id,
            'place' => Curtain::PLACE_RS485,
            'address' => 100,
            'group' => 100,
            'device_id' => $device->id,
        ]);

        return [
            'dev_type' => $devType,
            'device' => $device,
            'object' => $object,
            'curtain' => $curtain,
        ];
    }
}
