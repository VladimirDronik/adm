<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository
{
    public function getAll($pagination_count = 30)
    {
        return Setting::orderBy('name')->paginate($pagination_count);
    }

    public static function get($name)
    {
        return Setting::where('name', $name)->first()->value;
    }

    public static function getById($id)
    {
        return Setting::where('id', $id)->first();
    }

    public static function getByName($name)
    {
        return Setting::where('name', $name)->first();
    }

    public static function set($name, $value)
    {
        Setting::where('name', $name)->update(['value' => $value]);
    }
}
