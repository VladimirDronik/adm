<?php

namespace App\Repositories;

use App\Models\Pressurestat;

class PressurestatRepository
{
    public function getAll(int $perPage = 30)
    {
        return Pressurestat::with('relatedObject')
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function getAllToArray(): array
    {
        return Pressurestat::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
