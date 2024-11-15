<?php

namespace App\Services;

use App\Models\ObjType;
use App\Models\Usensor;
use App\Models\Manometr;
use App\Models\Hygrostat;
use App\Models\Lightstat;
use App\Models\Termostat;
use App\Models\Drycontact;
use App\Models\HomeObject;
use App\Models\Carbdioxide;
use App\Models\Carbmonoxide;
use App\Models\Motionsensor;
use App\Models\Pressurestat;

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

    public function getPressurestatsCount(): int
    {
        return Pressurestat::count();
    }

    public function getCarbdioxidesCount(): int
    {
        return Carbdioxide::count();
    }

    public function getSensorsCount(): int
    {
        return HomeObject::where('type', ObjType::TYPE_SENSOR)->count();
    }
}
