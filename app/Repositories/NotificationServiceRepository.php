<?php

namespace App\Repositories;

use App\Models\NotificationSettings;

class NotificationServiceRepository
{
    public function getAll(int $perPage = 30)
    {
        return NotificationSettings::orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
