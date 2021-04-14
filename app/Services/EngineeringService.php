<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 13.04.21
 * Time: 15:37
 */

namespace App\Services;

use App\Models\HomeObject;
use Illuminate\Support\Facades\DB;

class EngineeringService
{


    public function delete(int $idObject)
    {

        //HomeObject::deleteAutoObject(idObject);

        $object = HomeObject::findOrFail($idObject);

            DB::transaction(function () use (&$object, $idObject) {
                   HomeObject::deleteAutoObject($idObject);
                $object->delete();
            });

        return true;

    }
}