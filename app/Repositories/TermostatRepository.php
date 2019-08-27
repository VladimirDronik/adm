<?php

namespace App\Repositories;

use App\Models\Termostat;

class TermostatRepository {

    public function getAll($pagination_count = 30)
    {
        return Termostat::orderBy('id')->paginate($pagination_count);
    }
}