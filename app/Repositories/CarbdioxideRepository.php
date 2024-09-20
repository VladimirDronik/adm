<?php

namespace App\Repositories;

use App\Models\Carbdioxide;

class CarbdioxideRepository
{
    public function getAll(int $pages = 30)
    {
        return Carbdioxide::with('relatedObject')
            ->orderBy('id')
            ->paginate($pages);
    }

    public function getAllToArray(): array
    {
        return Carbdioxide::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}
