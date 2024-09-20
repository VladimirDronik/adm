<?php

namespace App\Repositories;

use App\Models\Usensor;

class UsensorRepository
{
    public function getAll(int $perPage = 30)
    {
        return Usensor::paginate($perPage);
    }

    public function getAllToArray(): array
    {
        return Usensor::select('id_object', 'name')
            ->orderBy('name')
            ->pluck('name', 'id_object')
            ->toArray();
    }

    public function getByTypesToArray(array $types): array
    {
        return Usensor::whereIn('type', $types)
            ->select('id_object', 'name')
            ->orderBy('name')
            ->pluck('name', 'id_object')
            ->toArray();
    }
}
