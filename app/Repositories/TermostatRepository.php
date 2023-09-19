<?php

namespace App\Repositories;

use App\Models\Termostat;

class TermostatRepository
{
    public function getAll($pagination_count = 30)
    {
        return Termostat::with('eobject')
            ->orderBy('id')
            ->paginate($pagination_count);
    }

    public function getAllWithIdObjectToArray()
    {
        return Termostat::all()
            ->pluck('name', 'id_object')
            ->toArray();
    }
}
