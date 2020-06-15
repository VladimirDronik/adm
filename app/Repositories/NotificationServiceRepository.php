<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 05.06.20
 * Time: 17:42
 */

namespace App\Repositories;

use App\Models\NotificationSettings;

class NotificationServiceRepository
{
    public function getAll($pagination_count = 30)
    {
        return NotificationSettings::orderBy('id', 'desc')->paginate($pagination_count);
    }

}