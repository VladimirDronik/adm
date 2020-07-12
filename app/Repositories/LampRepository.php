<?php

namespace App\Repositories;

use App\Models\Lamp;

class LampRepository {

    public function getAll($pagination_count = 30)
    {
        return Lamp::with('object')->orderBy('id', 'desc')->paginate($pagination_count);
    }
}