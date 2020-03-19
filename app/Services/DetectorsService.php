<?php

namespace App\Services;

use App\Models\Termostat;

class DetectorsService {

    public function getTermostatsCount(): int
    {
        return Termostat::count();
    }

    public function getUsensorsCount(): int
    {
        return Usensors::count();
    }
}