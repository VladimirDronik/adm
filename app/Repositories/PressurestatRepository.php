<?php

namespace App\Repositories;

use App\Models\Pressurestat;

class PressurestatRepository
{
    public function getAll($pages = 30)
    {
        return Pressurestat::with('relatedObject')
            ->orderBy('id')
            ->paginate($pages);
    }

    public function getAllToArray()
    {
        return Pressurestat::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
