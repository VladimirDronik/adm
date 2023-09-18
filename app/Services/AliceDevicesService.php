<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 02.05.21
 * Time: 17:08
 */

namespace App\Services;

use App\Models\Alice;

class AliceDevicesService
{
    /**
     * Обновляем данные об устройстве Алисы или обновляем запись о существующем устройстве
     */
    public static function addOrReplaceDevice($idObject, $name, $room)
    {
        if ($room == 0) {
            $room = null;
        }

        Alice::updateOrCreate(['id_object' => $idObject], ['name' => $name, 'room' => $room, 'active' => 1]);
    }

    public static function setActive($idObject, $statusActive)
    {
        $aliceDevice = Alice::where('id_object', $idObject)->first();

        if ($aliceDevice) {
            $aliceDevice->active = $statusActive;
            $aliceDevice->save();
        }

    }
}
