<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 02.05.21
 * Time: 15:43
 */

namespace App\Repositories;

use App\Models\Alice;

class AliceDevicesRepository
{
    /**
     * Получение имени и помещения для объекта
     */
    public function getNameAndRoomByObject($idObject)
    {

        $alice = Alice::select('name', 'room', 'active')->where('id_object', $idObject)->first();

        if (! $alice) {
            $alice['active'] = 0;
            $alice['name'] = '';
            $alice['room'] = 0;
        }

        return $alice;
    }
}
