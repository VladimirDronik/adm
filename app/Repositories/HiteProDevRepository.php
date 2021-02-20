<?php

namespace App\Repositories;

use App\Models\HiteproDev;


class HiteProDevRepository {

     public function getSwitchByDeviceId($idDevice){


         if($idDevice)
             return HiteproDev::where('id_controller', $idDevice)->where('type', 'switch')
                 ->orderBy('name')->get();
         else return null;
     }

    public function getSocketByDeviceId($idDevice){


        if($idDevice)
            return HiteproDev::where('id_controller', $idDevice)->where('type', 'socket')
                ->orderBy('name')->get();
        else return null;
    }

    public function getTermometrsByDeviceId($idDevice){


        if($idDevice)
            return HiteproDev::where('id_controller', $idDevice)->where('type', 'temperature')
                ->orderBy('name')->get();
        else return null;
    }

    public function getHPDevByDeviceId(int $device_id)
    {

            return HiteproDev::where('id_controller', $device_id)->where('type', 'switch')->orwhere('type', 'socket')
              ->orderBy('name')->get();
    }

}