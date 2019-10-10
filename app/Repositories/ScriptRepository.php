<?php

namespace App\Repositories;

use App\Models\Script;

class ScriptRepository {

    public function getAllToArray()
    {
        return Script::orderBy('name')->select('id', 'name')
            ->pluck('name', 'id')->toArray();
    }

    public function getByName($name, $pagination_count = 30)
    {
        $query = Script::query();

        if (!empty($name)) {
            $query->where('name','like','%'.$name.'%');
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }
}