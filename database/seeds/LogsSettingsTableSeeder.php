<?php

use Illuminate\Database\Seeder;
use App\Models\Logging;

class LogsSettingsTableSeeder extends Seeder
{
    private $settings;

    public function __construct()
    {
        $this->settings = Logging::pluck('point')->toArray();
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
                'point' => 'port',
                'value' => 1,
                'description' => 'Срабатывание портов'
            ],
            [
                'point' => 'button',
                'value' => 1,
                'description' => 'Сообщения кнопок / выключателей'
            ],
            [
                'point' => 'dimmer',
                'value' => 1,
                'description' => 'Сообщения диммера'
            ],
            [
                'point' => 'controller',
                'value' => 1,
                'description' => 'Сообщения контроллера'
            ],
            [
                'point' => 'sensor',
                'value' => 1,
                'description' => 'Сообщения сенсоров'
            ],
            [
                'point' => 'scripts',
                'value' => 1,
                'description' => 'Сообщения скриптов'
            ],
            [
                'point' => 'socket_server',
                'value' => 1,
                'description' => 'Сообщения сервера сокетов'
            ],
        ];

        $result_settings = [];

        foreach ($settings as $setting) {
            if (!in_array($setting['point'], $this->settings, true)) {
                $result_settings[] = $setting;
            }
        }

        if (count($result_settings)) {
            Logging::insert($result_settings);
        }
    }
}
