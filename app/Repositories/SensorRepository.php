<?php

namespace App\Repositories;

use App\Models\ObjType;
use App\Models\HomeObject;
use App\Models\SensorsParam;

class SensorRepository
{
    public function getAll(int $perPage = 30)
    {
        return HomeObject::with('sensors')
            ->where('type', ObjType::TYPE_SENSOR)
            ->paginate($perPage);
    }

    public function getAllToArray(): array
    {
        return HomeObject::where('type', ObjType::TYPE_SENSOR)
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getParamsBySensor(int $sensorId): array
    {
        return SensorsParam::where('object_id', $sensorId)
            ->pluck('name', 'id')
            ->toArray();
    }
}
