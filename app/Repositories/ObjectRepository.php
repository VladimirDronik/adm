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
        $objects = HomeObject::select('id','name')->orderBy('name')->pluck('name','id')->toArray();
        array_walk($objects, function (&$object, $key) { $object = $key.' - '.$object; });

        return $objects;
    }

    public function getByName($name, $pagination_count = 15)
    {
        $query = HomeObject::with('eview');

        if (!empty($name)) {
            $query->where('name','like','%'.$name.'%');
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }
}