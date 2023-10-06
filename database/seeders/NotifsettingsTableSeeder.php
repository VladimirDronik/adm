<?php

namespace Database\Seeders;

use App\Models\NotificationSettings;
use Illuminate\Database\Seeder;

class NotifsettingsTableSeeder extends Seeder
{
    private $settings;

    public function __construct()
    {
        $this->settings = NotificationSettings::pluck('name')->toArray();
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
                'name' => 'Оповещения о недоступности устройства',
                'type' => 'device_not_available',
                'priority' => 1,
                'message' => 'Устройство {$device->name} ({$device->ip}) недоступно',
            ],
            [
                'name' => 'Оповещения о доступности устройства',
                'type' => 'device_is_available',
                'priority' => 1,
                'message' => 'Устройство {$device->name} ({$device->ip}) снова доступно',
            ],
            [
                'name' => 'Оповещения об аварии термостата',
                'type' => 'termostat_alarm',
                'priority' => 1,
                'message' => 'Термостат {$termostat->name} вышел за границы диапазона.T={$termostat->temp}',
            ],
        ];

        $result_settings = [];

        foreach ($settings as $setting) {
            if (! in_array($setting['name'], $this->settings, true)) {
                $result_settings[] = $setting;
            }
        }

        if (count($result_settings)) {
            NotificationSettings::insert($result_settings);
        }
    }
}
