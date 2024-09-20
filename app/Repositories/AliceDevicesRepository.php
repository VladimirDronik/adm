<?php

namespace App\Repositories;

use App\Models\Alice;

class AliceDevicesRepository
{
    /**
     * Получение имени и помещения для объекта
     */
    public function getNameAndRoomByObject(int $idObject)
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
