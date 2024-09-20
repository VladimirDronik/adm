<?php

namespace App\Repositories;

use App\Models\Hygrostat;

class HygrostatRepository
{
    public function getAll(int $perPage = 30)
    {
        return Hygrostat::with('eobject')
            ->orderBy('id')
            ->paginate($perPage);
    }
}
