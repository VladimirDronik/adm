<?php

namespace App\Repositories;

use App\Models\Method;

class MethodRepository
{
    public function getAllToArray()
    {
        return Method::select('id', 'name')->orderBy('id')->pluck('name', 'id')->toArray();
    }

    public function getAllMethodsByObjectToArray($objectID)
    {
        return Method::select('id', 'name')->where('id_object', $objectID)->orderBy('id')->pluck('name', 'id')->toArray();

    }

    public function getObjectByMethod($idMethod)
    {

        if ($idMethod) {

            $return = Method::select('id_object')->where('id', $idMethod)->orderBy('id')->first();

            return $return->id_object;
        } else {
            return null;
        }
    }

    public static function getMethodByID($idMethod)
    {
        return Method::select('name', 'id_object')->where('id', $idMethod)->orderBy('id')->first();
    }
}
