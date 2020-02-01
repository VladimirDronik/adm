<?php

namespace App\Repositories;

use App\Models\DeviceSwitch;

class SwitchRepository {

    public function getAll($pagination_count = 30)
    {
        return DeviceSwitch::with('object')->orderBy('id', 'desc')->paginate($pagination_count);
    }
}