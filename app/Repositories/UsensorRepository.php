<?php

namespace App\Repositories;

use App\Models\Usensor;

class UsensorRepository
{
    public function getAll($pagination_count = 30)
    {
        return Usensor::with('eobject')
            ->orderBy('id')
            ->paginate($pagination_count);
    }

    public function getAllToArray()
    {
        return Usensor::select('id_object', 'name')
            ->orderBy('name')
            ->pluck('name', 'id_object')
            ->toArray();
    }

    public function getByTypesToArray(array $types)
    {
        return Usensor::whereIn('type', $types)
            ->select('id_object', 'name')
            ->orderBy('name')
            ->pluck('name', 'id_object')
            ->toArray();
    }
}
