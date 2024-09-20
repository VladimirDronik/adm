<?php

namespace App\Repositories;

use App\Models\Motionsensor;

class MotionsensorRepository
{
    public function getAll(int $perPage = 30)
    {
        return Motionsensor::with('object')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
