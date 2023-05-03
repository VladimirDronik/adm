<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository {

    public function getAll($pagination_count = 30)
    {
        return Setting::orderBy('name')->paginate($pagination_count);
    }

    static public function get($name)
    {
        return  Setting::where('name', $name)->first()->value;
    }

    static public function getById($id)
    {
        return  Setting::where('id', $id)->first();
    }

    static public function set($name, $value)
    {
        Setting::where('name', $name)->update(['value' => $value]);
    }
}