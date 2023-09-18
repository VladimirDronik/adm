<?php

namespace App\Repositories;

use App\Models\Carbmonoxide;

class CarbmonoxideRepository
{
    public function getAll($pagination_count = 30)
    {
        return Carbmonoxide::with('eobject')->orderBy('id')->paginate($pagination_count);
    }

    public function getAllToArray()
    {
        return Carbmonoxide::select('id', 'name')->orderBy('name')
            ->pluck('name', 'id')->toArray();
    }
}
