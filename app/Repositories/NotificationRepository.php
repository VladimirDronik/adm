<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository
{
    public function getByObject(int $idObject): ?Notification
    {
        return Notification::where('id_object', $idObject)->first();
    }
}
