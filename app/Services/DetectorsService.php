<?php

namespace App\Services;

use App\Models\Termostat;
use App\Models\Usensor;
use App\Models\Drycontact;

class DetectorsService {

    public function getTermostatsCount(): int
    {
        return Termostat::count();
    }

    public function getUsensorsCount(): int
    {
        return Usensor::count();
    }

    public function getDrycontactsCount(): int
    {
        return Drycontact::count();
    }
}