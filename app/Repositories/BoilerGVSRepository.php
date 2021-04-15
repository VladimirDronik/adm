<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 11.04.21
 * Time: 10:38
 */

namespace App\Repositories;
use App\Models\BoilerGVS;

class BoilerGVSRepository
{

    public function getBoiler($boilerIdObject)
    {
      return  BoilerGVS::where('id_object', $boilerIdObject)->first();
    }


}