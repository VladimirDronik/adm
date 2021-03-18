<?php

namespace App\Repositories;

use App\Models\Manometr;

class ManometrRepository {

    public function getAll($pagination_count = 30)
    {
        return Manometr::with('eobject')->orderBy('id')->paginate($pagination_count);
    }

    public function getAllToArray()
    {
        return Manometr::select('id', 'name')->orderBy('name')
            ->pluck('name','id')->toArray();
    }
}