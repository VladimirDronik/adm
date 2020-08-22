<?php

namespace App\Repositories;

use App\Models\HiteproDev;

class HiteProDevRepository {

     public function getRelaysByDeviceId($idDevice){


         if($idDevice)
             return HiteproDev::where('id_controller', $idDevice)->where('type', 'relay')
                 ->orderBy('name')->get();
         else return null;
     }



}