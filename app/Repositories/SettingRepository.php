<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository {

    public function getAll($pagination_count = 30)
    {
        return Setting::orderBy('name')->paginate($pagination_count);
    }
}