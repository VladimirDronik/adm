<?php

namespace App\Repositories;

use App\Models\Relay;

class RelayRepository
{
    public function getAll(int $perPage = 30)
    {
        return Relay::with('object')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
