<?php

namespace App\Repositories;

use App\Models\Regulator;

class RegulatorRepository
{
    public function getAll(int $perPage = 30)
    {
        return Regulator::with('object')
            ->orderByDesc('id')
            ->paginate($perPage);
    }
}
