<?php

namespace App\Repositories;

use App\Models\Manometr;

class ManometrRepository
{
    public function getAll(int $perPage = 30)
    {
        return Manometr::with('eobject')
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function getAllToArray(): array
    {
        return Manometr::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
