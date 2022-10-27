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
        return HomeObject::select('id', 'name')->orderBy('name')
            ->pluck('name','id')->toArray();
    }

    /**
     * Отдать всё инженерное оборудование
     */
    public function getAllEngineering($pagination_count = 30)
    {
        $engEquipments = array('boiler', 'boiler_gvs');

        $queryEquipments = HomeObject::query();

        foreach ($engEquipments as $equipment) {
            $queryEquipments->orwhere('objects.type', $equipment);
        }


        return $queryEquipments->orderBy('objects.name')->paginate($pagination_count);

    }


    public function getByName($name, $pagination_count = 30)
    {
        $query = HomeObject::query();

        if (!empty($name)) {
            $query->where('name', 'like', '%'.$name.'%');
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }



    public static function getNameById($idObject)
    {
        return HomeObject::select('name')->where('id', $idObject)->first();
    }
    
     public static function getById($idObject)
    {
        return HomeObject::where('id2', $idObject)->first();
    }

}
