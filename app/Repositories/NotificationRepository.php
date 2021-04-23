<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 24.05.20
 * Time: 7:48
 */

namespace App\Repositories;


use App\Models\Notification;

class NotificationRepository
{

    public function getByObject($idObject)
    {
        return Notification::where('id_object', $idObject)->first();
    }


}