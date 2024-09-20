<?php

namespace App\Repositories;

use App\Models\BoilerGVS;

class BoilerGVSRepository
{
    public function getBoiler(int $boilerIdObject): ?BoilerGVS
    {
        return BoilerGVS::where('id_object', $boilerIdObject)->first();
    }
}
