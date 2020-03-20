<?php

namespace App\Repositories;

use App\Models\Usensor;

class UsensorRepository {

    public function getAll($pagination_count = 30)
    {
        return Usensor::with('eobject')->orderBy('id')->paginate($pagination_count);
    }
}