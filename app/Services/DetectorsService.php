<?php

namespace App\Services;

use App\Models\Carbmonoxide;
use App\Models\Drycontact;
use App\Models\Hygrostat;
use App\Models\Lightstat;
use App\Models\Manometr;
use App\Models\Motionsensor;
use App\Models\Termostat;
use App\Models\Usensor;

class DetectorsService
{
    public function getTermostatsCount(): int
    {
        return Termostat::count();
    }

    public function getHygrostatCount(): int
    {
        return Hygrostat::count();
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

    public function getCarbMonoxideCount(): int
    {
        return Carbmonoxide::count();
    }

    public function getManometrCount(): int
    {

        return Manometr::count();
    }
}
