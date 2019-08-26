<?php

namespace App\Repositories;

use App\Models\HomeObject;

class ObjectRepository {

    public function getByName($name, $pagination_count = 15)
    {
        $query = HomeObject::with('eview');

        if (!empty($name)) {
            $query->where('name','like','%'.$name.'%');
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }
}