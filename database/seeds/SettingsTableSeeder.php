<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    private $settings;

    public function __construct()
    {
        $this->settings = Setting::pluck('name')->toArray();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'name' => 'heating_mode',
                'value' => 'eco',
                'comment' => 'План отопления дома: eco, night, normal'
            ],
            [
                'name' => 'night_mode',
                'value' => 'false',
                'comment' => 'Ночной режим'
            ],
            [
                'name' => 'eco_mode',
                'value' => 'false',
                'comment' => 'Режим экономии'
            ],
            [
                'name' => 'light_mode',
                'value' => 'day',
                'comment' => 'Режим освещения: night, day, evening'
            ],
            [
                'name' => 'graphdate',
                'value' => '365',
                'comment' => 'Сколько дней хранить информацию в графиках'
            ],
            [
                'name' => 'logging',
                'value' => 'DB',
                'comment' => 'Где хранить логи: file или DB'
            ],
            [
                'name' => 'storage_logs',
                'value' => '30',
                'comment' => 'Количество дней хранения логов'
            ],
            [
                'name' => 'guard_mode',
                'value' => 'false',
                'comment' => 'Режим охраны'
            ],
            [
                'name' => 'VPN',
                'value' => 'false',
                'comment' => 'Использование VPN'
            ],
        ];

        $result_settings = [];

        foreach ($settings as $setting) {
            if (!in_array($setting['name'], $this->settings, true)) {
                $result_settings[] = $setting;
            }
        }

        if (count($result_settings)) {
            Setting::insert($result_settings);
        }
    }
}
