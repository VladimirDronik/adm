<?php

namespace App\Repositories;

use App\Models\Boiler;

class BoilerRepository
{
    public function getBoiler(int $boilerIdObject): ?Boiler
    {
        return Boiler::where('id_object', $boilerIdObject)
            ->with(['object', 'object.methods'])
            ->first();
    }
}
