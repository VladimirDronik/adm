<?php

namespace App\Repositories;

use App\Models\Dimmer;

class DimmerRepository
{
    public function getAll(int $perPage = 30)
    {
        return Dimmer::with('object')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
