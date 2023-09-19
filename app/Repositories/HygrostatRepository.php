<?php

namespace App\Repositories;

use App\Models\Hygrostat;

class HygrostatRepository
{
    public function getAll($pagination_count = 30)
    {
        return Hygrostat::with('eobject')
            ->orderBy('id')
            ->paginate($pagination_count);
    }
}
