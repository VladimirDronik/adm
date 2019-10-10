<?php

namespace App\Repositories;

use App\Models\Script;

class ScriptRepository {

    public function getAllToArray()
    {
        return Script::orderBy('name')->select('id', 'name')
            ->pluck('name', 'id')->toArray();
    }
}