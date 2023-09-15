<?php

namespace Tests\TestData;

use App\Models\Setting;

class SettingsData
{
    /**
     * Генератор сущностей для настроек
     *
     * @return array
     */
    public function generateSetting(): array
    {
        $timeZone = Setting::create([
            'name' => 'time_zone',
            'value' => 'Europe/Moscow',
            'comment' => 'Тестовая настройка часового пояса',
        ]);

        $setting = Setting::create([
            'name' => 'test_setting',
            'value' => 'test_value',
            'comment' => 'Тестовая настройка',
        ]);

        return [
            'time_zone' => $timeZone,
            'setting' => $setting,
        ];
    }
}
