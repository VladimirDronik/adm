<?php

namespace App\Repositories;

use App\Models\HiteproDev;
use Illuminate\Database\Eloquent\Collection;

class HiteProDevRepository
{
    public function getSwitchByDeviceId(int $idDevice): Collection
    {
        return HiteproDev::where('id_controller', $idDevice)
            ->where('type', 'switch')
            ->orderBy('name')
            ->get();
    }

    public function getSocketByDeviceId(int $idDevice): Collection
    {
        return HiteproDev::where('id_controller', $idDevice)
            ->where('type', 'socket')
            ->orderBy('name')
            ->get();
    }

    public function getTermometrsByDeviceId(int $idDevice): Collection
    {
        return HiteproDev::where('id_controller', $idDevice)
            ->where('type', 'temperature')
            ->orderBy('name')
            ->get();
    }

    public function getTransmittersByDeviceId(int $idDevice): Collection
    {
        return HiteproDev::where('id_controller', $idDevice)
            ->where('type', 'transmitter')
            ->orderBy('name')
            ->get();
    }

    public function getHPDevByDeviceId(int $deviceId): Collection
    {
        return HiteproDev::where('id_controller', $deviceId)
            ->where('type', 'switch')
            ->orwhere('type', 'socket')
            ->orderBy('name')
            ->get();
    }

    public static function getIDByDeviceID(int $deviceId): Collection
    {
        return HiteproDev::where('id_controller', $deviceId)
            ->where('type', 'switch')
            ->orwhere('type', 'socket')
            ->orderBy('name')
            ->get();
    }
}
