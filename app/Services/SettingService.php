<?php

namespace App\Services;

use App\Models\Setting;

class SettingService {

    public function delete(int $id)
    {
        return Setting::destroy($id);
    }

    public function prepareSetting(Setting $setting, array $data)
    {
        $setting->name = trim($data['name']);
        $setting->value = trim($data['value']);
        $setting->comment = trim($data['comment']);
    }

    public function store(array $data)
    {
        $setting = new Setting();
        $this->prepareSetting($setting, $data);
        $setting->save();

        return $setting->id;
    }

    public function update(Setting $setting, array $data)
    {


        $this->prepareSetting($setting, $data);
        $setting->save();

        return $setting->id;
    }
}