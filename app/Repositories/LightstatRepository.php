<?php

namespace App\Repositories;

use App\Models\Lightstat;

class LightstatRepository
{
    public function getAll(int $perPage = 30)
    {
        return Lightstat::with('eobject')
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function getAllToArray(): array
    {
        return Lightstat::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
