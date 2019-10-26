<?php

namespace App\Repositories;

use App\Models\HomeObject;

class ObjectRepository {

    public function getAll()
    {
        return HomeObject::orderBy('name')->get();
    }

    public function getAllToArray()
    {
        return  HomeObject::select('id','name')->orderBy('name')->pluck('name','id')->toArray();
    }

    public function getByName($name, $pagination_count = 30)
    {
        $query = HomeObject::query();

        if (!empty($name)) {
            $query->where('name','like','%'.$name.'%');
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }
}