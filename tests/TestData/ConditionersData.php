<?php

namespace Tests\TestData;

use App\Models\Conditioner;
use App\Models\ConditionerKind;
use App\Models\ConditionerModel;
use App\Models\ConditionerVendor;
use App\Models\Device;
use App\Models\DevType;
use App\Models\HomeObject;
use App\Models\Room;

class ConditionersData
{
    /**
     * Генератор сущностей для кондиционера
     *
     * @return array
     */
    public function generateConditioner(): array
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

        $conditionerVendor = ConditionerVendor::create([
            'name' => 'Тестовый производитель',
        ]);

        $conditionerKind = ConditionerKind::create([
            'min' => 16,
            'max' => 31,
            'precision' => 1,
            'operationModes' => '{"modes": ["cool", "heat", "dry"]}',
            'fanModes' => '{"modes": ["low", "mid", "high"]}',
        ]);

        $conditionerModel = ConditionerModel::create([
            'name' => 'Тестовая модель',
            'vendor' => $conditionerVendor->id,
            'kind' => $conditionerKind->id,
        ]);

        $object = HomeObject::create([
            'name' => 'Тестовый кондиционер',
            'type' => 'conditioner',
            'status' => 'on',
        ]);

        $room = Room::create([
            'name' => 'Тестовая комната',
            'image' => 'noimage.png',
            'style' => 'grey',
            'sort' => 1,
            'is_group' => 0,
        ]);

        $conditioner = Conditioner::create([
            'model' => $conditionerModel->id,
            'device_id' => $device->id,
            'id_object' => $object->id,
            'wb_mir' => '111111',
            'id_room' => $room->id,
        ]);

        return [
            'dev_type' => $devType,
            'device' => $device,
            'object' => $object,
            'conditioner_kind' => $conditionerKind,
            'conditioner_vendor' => $conditionerVendor,
            'conditioner_model' => $conditionerModel,
            'room' => $room,
            'conditioner' => $conditioner,
        ];
    }
}
