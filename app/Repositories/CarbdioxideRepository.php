<?php

namespace App\Repositories;

use App\Models\Carbdioxide;

class CarbdioxideRepository
{
    public function getAll($pages = 30)
    {
        return Carbdioxide::with('relatedObject')
            ->orderBy('id')
            ->paginate($pages);
    }

    public function getAllToArray()
    {
        return Carbdioxide::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
