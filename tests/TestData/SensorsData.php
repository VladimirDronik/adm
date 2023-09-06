<?php

namespace Tests\TestData;

use App\Models\Carbmonoxide;
use App\Models\Device;
use App\Models\DevType;
use App\Models\Drycontact;
use App\Models\HomeObject;
use App\Models\Hygrostat;
use App\Models\Lightstat;
use App\Models\Motionsensor;
use App\Models\Port;
use App\Models\Termostat;
use App\Models\Usensor;

class SensorsData
{
    /**
     * Генератор сущностей для термостата
     *
     * @return array
     */
    public function generateTermostat(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовый Термостат',
            'type' => 'termostat',
            'status' => 'on',
        ]);

        $termostat = Termostat::create([
            'name' => 'Тестовый Термостат',
            'id_object' => $object->id,
            'optimal' => 25,
            'gisteresis' => 1,
            'thermostat' => 1,
            'min_threshold' => 10,
            'max_threshold' => 20,
            'min_alarm' => 5,
            'max_alarm' => 30,
        ]);

        return [
            'object' => $object,
            'termostat' => $termostat,
        ];
    }

    /**
     * Генератор сущностей для гигростата
     *
     * @return array
     */
    public function generateHygrostat(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовый Гигростат',
            'type' => 'hygrostat',
            'status' => 'on',
        ]);

        $hygrostat = Hygrostat::create([
            'name' => 'Тестовый Гигростат',
            'id_object' => $object->id,
            'optimal' => 25,
            'gisteresis' => 1,
            'type' => 1,
            'min_threshold' => 10,
            'max_threshold' => 20,
            'min_alarm' => 5,
            'max_alarm' => 30,
        ]);

        return [
            'object' => $object,
            'hygrostat' => $hygrostat,
        ];
    }

    /**
     * Генератор сущностей для светостата
     *
     * @return array
     */
    public function generateLightstat(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовый Светостат',
            'type' => 'lightstat',
            'status' => 'on',
        ]);

        $lightstat = Lightstat::create([
            'name' => 'Тестовый Светостат',
            'id_object' => $object->id,
            'current' => 10,
            'optimal' => 25,
            'gisteresis' => 1,
            'mode' => 1,
            'min_threshold' => 10,
            'max_threshold' => 20,
            'min_alarm' => 5,
            'max_alarm' => 30,
        ]);

        return [
            'object' => $object,
            'lightstat' => $lightstat,
        ];
    }

    /**
     * Генератор сущностей для датчика движения
     *
     * @return array
     */
    public function generateMotionsensor(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовый Датчик движения',
            'type' => 'motionsensor',
            'status' => 'on',
        ]);

        $motionsensor = Motionsensor::create([
            'name' => 'Тестовый Датчик движения',
            'id_object' => $object->id,
        ]);

        return [
            'object' => $object,
            'motionsensor' => $motionsensor,
        ];
    }

    /**
     * Генератор сущностей для универсального датчика
     *
     * @return array
     */
    public function generateUsensor(): array
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
            'status' => 'IN',
            'comment' => 'Тестовый порт',
        ]);

        $object = HomeObject::create([
            'name' => 'Тестовый универсальный датчик',
            'type' => 'usensor',
            'status' => 'on',
        ]);

        $usensor = Usensor::create([
            'id_object' => $object->id,
            'device_id' => $device->id,
            'name' => 'Тестовый универсальный датчик',
            'type' => 'htu21d',
            'device_id' => $device->id,
            'port_SCL' => $port->id,
            'port_SDA' => $port->id,
        ]);

        return [
            'dev_type' => $devType,
            'device' => $device,
            'port' => $port,
            'object' => $object,
            'usensor' => $usensor,
        ];
    }

    /**
     * Генератор сущностей для сухого контакта
     *
     * @return array
     */
    public function generateDrycontact(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовый сухой контакт',
            'type' => 'drycontact',
            'status' => 'on',
        ]);

        $drycontact = Drycontact::create([
            'id_object' => $object->id,
            'name' => 'Тестовый сухой контакт',
        ]);

        return [
            'object' => $object,
            'drycontact' => $drycontact,
        ];
    }

    /**
     * Генератор сущностей для датчика УГ
     *
     * @return array
     */
    public function generateCarbmonoxide(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовый датчик УГ',
            'type' => 'carbsens',
            'status' => 'on',
        ]);

        $carbmonoxide = Carbmonoxide::create([
            'id_object' => $object->id,
            'name' => 'Тестовый датчик УГ',
            'cur_value' => 0,
            'low_value' => 50,
            'high_value' => 100,
            'calibration' => 2,
        ]);

        return [
            'object' => $object,
            'carbmonoxide' => $carbmonoxide,
        ];
    }
}
