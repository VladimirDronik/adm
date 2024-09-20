<?php

namespace App\Repositories;

use App\Models\Termostat;

class TermostatRepository
{
    public function getAll(int $perPage = 30)
    {
        return Termostat::with('eobject')
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function getAllWithIdObjectToArray(): array
    {
        return Termostat::all()
            ->pluck('name', 'id_object')
            ->toArray();
    }
}
