<?php

namespace App\Repositories;

use App\Models\Virtual;

class VirtualRepository
{
    public function getAll(int $perPage = 30)
    {
        return Virtual::with('object')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
