<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository
{
    public function getAll(int $perPage = 30)
    {
        return Setting::orderBy('name')
            ->paginate($perPage);
    }

    public static function get(string $name)
    {
        return Setting::where('name', $name)
            ->first()
            ?->value;
    }

    public static function getById(int $id): ?Setting
    {
        return Setting::find($id);
    }

    public static function getByName(string $name): ?Setting
    {
        return Setting::where('name', $name)->first();
    }

    public static function set(string $name, ?string $value)
    {
        Setting::where('name', $name)
            ->update(['value' => $value]);
    }
}
