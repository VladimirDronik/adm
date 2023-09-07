<?php

namespace Tests\TestData;

use App\Models\Boiler;
use App\Models\BoilerGVS;
use App\Models\BoilerManual;
use App\Models\BoilerWater;
use App\Models\HomeObject;

class EngineeringData
{
    /**
     * Генератор сущностей для котла
     *
     * @return array
     */
    public function generateBoiler(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовый котел',
            'type' => 'boiler',
            'status' => 1,
        ]);

        $boiler = Boiler::create([
            'name' => 'Тестовый котел',
            'id_object' => $object->id,
            'protocol' => 'ebus',
            'ip_address' => '161.165.20.179',
            'target_water_temp' => Boiler::DEFAULT_GVS_TEMP,
            'mode' => Boiler::PROP_MANUALMODE,
            'thermostat' => 0,
            'boiler' => 1,
            'lock' => 0,
        ]);

        $boilerWater = BoilerWater::create([
            'id_object' => $boiler->id_object,
            'min_value' => BoilerWater::MIN_VALUE,
            'max_value' => BoilerWater::MAX_VALUE,
        ]);

        $boilerManual = BoilerManual::create([
            'id_object' => $boiler->id_object,
            'min_value' => BoilerManual::MIN_VALUE,
            'max_value' => BoilerManual::MAX_VALUE,
            'set_value' => BoilerManual::DEFAULT_SET_VALUE,
        ]);

        return [
            'object' => $object,
            'boiler' => $boiler,
            'boiler_water' => $boilerWater,
            'boiler_manual' => $boilerManual,
        ];
    }

    /**
     * Генератор сущностей для котла ГВС
     *
     * @return array
     */
    public function generateBoilerGvs(): array
    {
        $object = HomeObject::create([
            'name' => 'Тестовый котел ГВС',
            'type' => 'boiler_gvs',
            'status' => 1,
        ]);

        $boilerGvs = BoilerGVS::create([
            'name' => 'Тестовый котел ГВС',
            'id_object' => $object->id,
            'model' => 'proterm',
            'ip_address' => '161.165.20.179',
            'mode' => Boiler::PROP_AUTOMODE,
        ]);

        return [
            'object' => $object,
            'boiler_gvs' => $boilerGvs,
        ];
    }
}
