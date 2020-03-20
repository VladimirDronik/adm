<?php

namespace App\Services;

use App\Models\Termostat;
use App\Models\Usensor;

class DetectorsService {

    public function getTermostatsCount(): int
    {
        return Termostat::count();
    }

    public function getUsensorsCount(): int
    {
        return Usensor::count();
    }
}