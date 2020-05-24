<?php

namespace App\Services;

use App\Models\Motionsensor;
use App\Models\Termostat;
use App\Models\Usensor;
use App\Models\Drycontact;
use App\Models\Lightstat;

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

    public function getLightstatsCount(): int
    {
        return Lightstat::count();
    }

    public function getMotionsensorsCount(): int
    {
        return Motionsensor::count();
    }

}