<?php

namespace App\Repositories;

use App\Models\Conditioner;

class ConditionerRepository
{
    public function getAll($pagination_count = 30)
    {
        return Conditioner::select(['rooms.name AS room',  'conditioners.id AS id', 'conditioners_models.name AS model',
            'conditioners_vendors.name AS vendor'])
            ->join('conditioners_models', 'conditioners.model', '=', 'conditioners_models.id', 'inner')
            ->join('conditioners_vendors', 'conditioners_models.vendor', '=', 'conditioners_vendors.id', 'inner')
            ->with('object')
            ->join('rooms', 'conditioners.id_room', '=', 'rooms.id', 'left')
            ->orderBy('conditioners.id', 'desc')
            ->paginate($pagination_count);
    }
}