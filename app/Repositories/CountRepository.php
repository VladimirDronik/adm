<?php

namespace App\Repositories;

use App\Models\Count;

class CountRepository
{
    public function getAll($pagination_count = 30)
    {
        return Count::with('object')->orderBy('id', 'desc')->paginate($pagination_count);
    }
}
