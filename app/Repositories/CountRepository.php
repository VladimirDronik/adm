<?php

namespace App\Repositories;

use App\Models\Count;

class CountRepository
{
    public function getAll(int $perPage = 30)
    {
        return Count::with('object')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
