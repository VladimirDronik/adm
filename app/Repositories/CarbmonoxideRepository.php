<?php

namespace App\Repositories;

use App\Models\Carbmonoxide;

class CarbmonoxideRepository
{
    public function getAll(int $perPage = 30)
    {
        return Carbmonoxide::with('eobject')
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function getAllToArray(): array
    {
        return Carbmonoxide::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
