<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
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
                'comment' => 'Сколько дней хранить информацию о температуре в графиках'
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
        ];

        Setting::insert($settings);
    }
}
