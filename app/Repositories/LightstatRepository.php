<?php

namespace App\Repositories;

use App\Models\Lightstat;

class LightstatRepository
{
    public function getAll($pagination_count = 30)
    {
        return Lightstat::with('eobject')
            ->orderBy('id')
            ->paginate($pagination_count);
    }

    public function getAllToArray()
    {
        return Lightstat::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
