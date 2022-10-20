<?php

namespace App\Services;

use App\Models\Setting;
use App\Repositories\SettingRepository;

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

    /**
     * Отдает значение настройки по имени\
     *
     * @param string $name
     */
    static public function get(string $name)
    {
       return SettingRepository::get($name);
    }

    /**
     * Устанавливает значение для выбранной настройки
     *
     * @param string $name
     * @param string $value
     */
    static public function set(string $name, string $value)
    {
        SettingRepository::set($name, $value);
    }
}