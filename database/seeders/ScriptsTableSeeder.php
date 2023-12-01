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
            'name' => 'Проверка термостата',
            'link' => 'check_termostat.php', // используется в TermostatObjectService
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getCheckHygrostatScript(): array
    {
        return [
            'name' => 'Проверка гигростата',
            'link' => 'check_hygrostat.php', // используется в HygrostatObjectService
            'count' => 0,
            'system' => 1,
        ];
    }

    public static function getCheckLightstatScript(): array
    {
        return [
            'name' => 'Проверка светостата',
            'link' => 'check_lightstat.php', // используется в LightstatObjectService
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
            [
                'name' => 'Выключить диммер',
                'link' => 'off_dimmer.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Включить диммер на последнем уровне яркости',
                'link' => 'on_dimmer.php',
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
                'link' => 'open_curtain.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Закрыть штору',
                'link' => 'close_curtain.php',
                'count' => 0,
                'system' => 1,
            ],
            [
                'name' => 'Открыть штору на %',
                'link' => 'open_half_curtain.php',
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

    private function getScripts(): array
    {
        $scripts = self::getDimmerScripts();

        $scripts = array_merge($scripts, self::getCurtainScripts());
        $scripts = array_merge($scripts, self::getLockScripts());
        $scripts = array_merge($scripts, self::getYandexStationScripts());
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
