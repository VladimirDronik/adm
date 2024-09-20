<?php

namespace App\Repositories;

use App\Models\Lock;

class LockRepository
{
    public function getAll(int $perPage = 30)
    {
        return Lock::with('object')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
