<?php

namespace App\Repositories;

use App\Models\Curtain;

class CurtainRepository
{
    public function getAll(int $perPage = 30)
    {
        return Curtain::with('object')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
