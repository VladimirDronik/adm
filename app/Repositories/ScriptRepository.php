<?php

namespace App\Repositories;

use App\Models\Script;

class ScriptRepository {

    public function getAllToArray()
    {
        return Script::orderBy('name')->select('id', 'name')
            ->pluck('name', 'id')->toArray();
    }

    public function getByName($name, bool $with_system = true, $pagination_count = 30)
    {
        $query = Script::withCount(['systemMethods']);

        if (!empty($name)) {
            $query->where('name','like','%'.$name.'%');
        }

        if (!$with_system) {
            $query->where('system', 0);
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }


    public static function getIdByLink($link) {

        return Script::select('id')->where('link','=',$link)->first();
    }
}