<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 11.04.21
 * Time: 10:38
 */

namespace App\Repositories;

use App\Models\Boiler;

class BoilerRepository
{
    public function getBoiler($boilerIdObject)
    {
        return Boiler::where('id_object', $boilerIdObject)->first();
    }
}
