<?php

namespace App\Repositories;

use App\Models\HiteproDev;

class HiteProDevRepository
{
    public function getSwitchByDeviceId($idDevice)
    {
        if ($idDevice) {
            return HiteproDev::where('id_controller', $idDevice)
                ->where('type', 'switch')
                ->orderBy('name')
                ->get();
        } else {
            return null;
        }
    }

    public function getSocketByDeviceId($idDevice)
    {
        if ($idDevice) {
            return HiteproDev::where('id_controller', $idDevice)
                ->where('type', 'socket')
                ->orderBy('name')
                ->get();
        } else {
            return null;
        }
    }

    public function getTermometrsByDeviceId($idDevice)
    {
        if ($idDevice) {
            return HiteproDev::where('id_controller', $idDevice)
                ->where('type', 'temperature')
                ->orderBy('name')
                ->get();
        } else {
            return null;
        }
    }

    public function getTransmittersByDeviceId($idDevice)
    {
        if ($idDevice) {
            return HiteproDev::where('id_controller', $idDevice)
                ->where('type', 'transmitter')
                ->orderBy('name')
                ->get();
        } else {
            return null;
        }
    }

    public function getHPDevByDeviceId(int $device_id)
    {
        return HiteproDev::where('id_controller', $device_id)
            ->where('type', 'switch')
            ->orwhere('type', 'socket')
            ->orderBy('name')
            ->get();
    }

    public static function getIDByDeviceID($deviceID)
    {
        return HiteproDev::where('id_controller', $deviceID)
            ->where('type', 'switch')
            ->orwhere('type', 'socket')
            ->orderBy('name')
            ->get();
    }
}
