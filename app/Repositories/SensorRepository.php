<?php

namespace App\Repositories;

use App\Models\ObjType;
use App\Models\HomeObject;

class SensorRepository
{
    public function getAll(int $perPage = 30)
    {
        return HomeObject::with('sensors')
            ->where('type', ObjType::TYPE_SENSOR)
            ->paginate($perPage);
    }
}
