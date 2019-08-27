<?php

namespace App\Repositories;

use App\Models\Termostat;

class TermostatRepository {

    public function getAll()
    {
        return Termostat::orderBy('id')->get();
    }
}