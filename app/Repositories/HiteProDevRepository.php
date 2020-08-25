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



}