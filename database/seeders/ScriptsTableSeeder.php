<?php

namespace Database\Seeders;

use App\Models\Script;
use Illuminate\Database\Seeder;

class ScriptsTableSeeder extends Seeder
{
    private $scripts;

    public function __construct()
    {
        $this->scripts = Script::pluck('name')->toArray();
    }

    public static function getResetGraphsScript(): array
    {
        return [
            'name' => 'Очистка графиков',
            'link' => 'reset_graphs.php',
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getCheckCountScript(): array
    {
        return [
            'name' => 'Проверка счетчика',
            'link' => 'check_count.php',
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getResetCountScript(): array
    {
        return [
            'name' => 'Сброс счетчика',
            'link' => 'reset_count.php',
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getCheckTermostatScript(): array
    {
        return [
            'name' => 'Проверка датчика температуры',
            'link' => 'check_termostat.php', // используется в TermostatObjectService
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getCheckHygrostatScript(): array
    {
        return [
            'name' => 'Проверка датчика влажности',
            'link' => 'check_hygrostat.php', // используется в HygrostatObjectService
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getCheckLightstatScript(): array
    {
        return [
            'name' => 'Проверка датчика освещенности',
            'link' => 'check_lightstat.php', // используется в LightstatObjectService
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getCheckPressurestatScript(): array
    {
        return [
            'name' => 'Проверка датчика давления',
            'link' => 'check_pressure.php', // используется в PressurestatObjectService
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getCheckUsensorScript(): array
    {
        return [
            'name' => 'Проверка универсального датчика',
            'link' => 'check_usensor.php', // используется в UsensorObjectService
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getDrycontactsScript(): array
    {
        return [

            'name' => 'Проверка текущего состояния контакта',
            'link' => 'check_drycontact.php',
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getMotionsensorScript(): array
    {
        return [

            'name' => 'Срабатывание датчика движения',
            'link' => 'run_motionsensor.php',
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getCheckCarbmonoxideScript(): array
    {
        return [
            'name' => 'Проверка датчика УГ',
            'link' => 'check_carbmonoxide.php',
            'count' => 0,
            'system' => 1,
        ];

    }

    public static function getCheckManometrScript(): array
    {
        return [
            'name' => 'Проверка манометра',
            'link' => 'check_manometr.php',
            'count' => 0,
            'system' => 1,
        ];

    }

    private static function deleteLogsScript(): array
    {

        return [
            'name' => 'Удаление старых логов',
            'link' => 'delete_logs.php',
            'count' => 0,
            'system' => 1,
        ];

    }

    public static function getCheckBoilerScript(): array
    {
        return [
            'name' => 'Проверка котла',
            'link' => 'check_boiler.php', // используется в BoilerObjectService
            'count' => 0,
            'system' => 1,
        ];
    }

    /**
     * Изменение скриптов в этой функции влияет на методы, которые
     * автоматически создаются для объекта диммера
     */
    public static function getDimmerScripts(): array
    {
        return [
            [
                'name' => 'Включить диммер',
                'link' => 'on_dimmer.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Выключить диммер',
                'link' => 'off_dimmer.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Увеличить яркость диммера',
                'link' => 'up_dimmer.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Уменьшить яркость диммера',
                'link' => 'down_dimmer.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Установить яркость диммера',
                'link' => 'set_dimmer.php',
                'count' => 0,
                'system' => 1,
            ],
        ];
    }

    /**
     * Изменение скриптов в этой функции влияет на методы, которые
     * автоматически создаются для объекта шторы
     */
    public static function getCurtainScripts(): array
    {
        return [
            [
                'name' => 'Открыть штору',
                'link' => 'curtain_open.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Закрыть штору',
                'link' => 'curtain_close.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Открыть штору на %',
                'link' => 'curtain_set_percent.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Сменить направление вращения',
                'link' => 'curtain_change_direction.php',
                'count' => 0,
                'system' => 1,
            ],
        ];
    }

    /**
     * Изменение скриптов в этой функции влияет на методы, которые
     * автоматически создаются для объекта замки
     */
    public static function getLockScripts(): array
    {
        return [
            [
                'name' => 'Открыть замок',
                'link' => 'open_lock.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Закрыть замок',
                'link' => 'close_lock.php',
                'count' => 0,
                'system' => 1,
            ],
        ];
    }

    /**
     * Скрипты для яндекс станций
     *
     * @return array
     */
    public static function getYandexStationScripts(): array
    {
        return [
            [
                'name' => 'Скрипт команды "Сказать"',
                'link' => 'yandex_station_say.php',
                'count' => 0,
                'system' => 1
            ],
            [
                'name' => 'Скрипт команды "CMD"',
                'link' => 'yandex_station_cmd.php',
                'count' => 0,
                'system' => 1
            ],
        ];
    }

    /**
     * Изменение скриптов в этой функции влияет на методы, которые
     * автоматически создаются для объекта лед ленты
     */
    public static function getLedTapeScripts(): array
    {
        return [
            [
                'name' => 'Включить LED ленту',
                'link' => 'led_on.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Выключить LED ленту',
                'link' => 'led_off.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Переключить LED ленту',
                'link' => 'led_sw.php',
                'count' => 0,
                'system' => 1,
            ],
        ];
    }

    /**
     * Скрипты для камер
     *
     * @return array
     */
    public static function getCameraScripts(): array
    {
        return [
            'name' => 'Превью камер',
            'link' => 'get_rtsp_snapshots.php',
            'count' => 0,
            'system' => 1
        ];
    }

    /**
     * Скрипты для устройств DALI
     *
     * @return array
     */
    public static function getDaliDeviceScripts(): array
    {
        return [
            [
                'name' => 'Выключить устройство DALI',
                'link' => 'dali_off.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Включить устройство DALI',
                'link' => 'dali_on.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Смена состояния устройства DALI',
                'link' => 'dali_sw.php',
                'count' => 0,
                'system' => 1,
            ],
        ];
    }

    private function getScripts(): array
    {
        $scripts = self::getDimmerScripts();

        $scripts = array_merge($scripts, self::getCurtainScripts());
        $scripts = array_merge($scripts, self::getLockScripts());
        $scripts = array_merge($scripts, self::getYandexStationScripts());
        $scripts = array_merge($scripts, self::getDaliDeviceScripts());
        $scripts = array_merge($scripts, self::getLedTapeScripts());
        $scripts[] = self::getResetGraphsScript();
        $scripts[] = self::getCheckCountScript();
        $scripts[] = self::getResetCountScript();
        $scripts[] = self::getCheckTermostatScript();
        $scripts[] = self::getCheckUsensorScript();
        $scripts[] = self::getDrycontactsScript();
        $scripts[] = self::getMotionsensorScript();
        $scripts[] = self::getCheckCarbmonoxideScript();
        $scripts[] = self::deleteLogsScript();
        $scripts[] = self::getCheckManometrScript();
        $scripts[] = self::getCheckBoilerScript();
        $scripts[] = self::getCameraScripts();

        return $scripts;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scripts = $this->getScripts();
        $result_scripts = [];

        foreach ($scripts as $script) {
            if (! in_array($script['name'], $this->scripts, true)) {
                $result_scripts[] = $script;
            }
        }

        if (count($result_scripts)) {
            Script::insert($result_scripts);
        }
    }
}
