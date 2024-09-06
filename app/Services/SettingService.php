<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Repositories\SettingRepository;

class SettingService
{
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

        if ($setting->name == 'time_zone') {
            $this->changeTimeZone($setting->value);
        }

        return $setting->id;
    }

    public function update(Setting $setting, array $data)
    {
        $this->prepareSetting($setting, $data);
        $setting->save();

        if ($setting->name == 'time_zone') {
            $this->changeTimeZone($setting->value);
        }

        return $setting->id;
    }

    /**
     * Отдает значение настройки по имени\
     */
    public static function get(string $name)
    {
        return SettingRepository::get($name);
    }

    /**
     * Устанавливает значение для выбранной настройки
     */
    public static function set(string $name, string $value)
    {
        SettingRepository::set($name, $value);
    }

    /**
     * Производит замену часового пояса в системе, php и laravel
     */
    private function changeTimeZone(string $timeZone)
    {
        // Замена часового пояса в laravel
        $path = base_path('.env');

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'APP_TIMEZONE='.config('app.timezone'), 'APP_TIMEZONE='.$timeZone, file_get_contents($path)
            ));
        }

        // Замена часового пояса в системе
        exec('rm -rf /etc/localtime');
        exec("ln -snf /usr/share/zoneinfo/$timeZone /etc/localtime && echo $timeZone > /etc/timezone");

        // Замена часового пояса в php
        exec("sed -i 's,.*date.timezone =.*,date.timezone = '\"$timeZone\"',g' /etc/php/7.4/fpm/php.ini");
        exec("sed -i 's,.*date.timezone =.*,date.timezone = '\"$timeZone\"',g' /etc/php/7.4/cli/php.ini");

        DB::statement("SET global time_zone = '$timeZone'");
    }

    /**
     * Генерирует id сервера
     */
    public function generateServerId(string $settingId): array
    {
        $setting = Setting::find($settingId);

        if (! $setting) {
            return [
                'result' => false,
                'message' => 'Параметр ID сервера не найден',
            ];
        }

        if ($setting->name != 'server_id') {
            return [
                'result' => false,
                'message' => 'Данный параметр не является ID сервера',
            ];
        }

        if ($setting->value) {
            return [
                'result' => false,
                'message' => 'ID сервера уже был сгенерирован ранее',
            ];
        }

        $setting->update([
            'value' => uniqid(rand()),
        ]);

        return [
            'result' => true,
            'message' => 'ID сервера сгенерирован',
        ];
    }
}
