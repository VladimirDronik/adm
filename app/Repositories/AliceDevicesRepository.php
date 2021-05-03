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
     * @param $idObject
     */
    public function getNameAndRoomByObject($idObject)
    {
        return Alice::select('name', 'room', 'active')->where('id_object', $idObject)->first();
    }


}