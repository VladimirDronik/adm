<?php

use Illuminate\Database\Seeder;
use App\Models\Script;

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
            'system' => 1
        ];
    }

    public static function getCheckCountScript(): array
    {
        return [
            'name' => 'Проверка счетчика',
            'link' => 'check_count.php',
            'count' => 0,
            'system' => 1
        ];
    }

    public static function getResetCountScript(): array
    {
        return [
            'name' => 'Сброс счетчика',
            'link' => 'reset_count.php',
            'count' => 0,
            'system' => 1
        ];
    }

    /**
     * Изменение скриптов в этой функции влияет на методы, которые
     * автоматически создаются для объекта диммера
     *
     * @return array
     */
    public static function getDimmerScripts(): array
    {
        return [
            [
                'name' => 'Включить диммер',
                'link' => 'on_dimmer.php',
                'count' => 0,
                'system' => 1
            ],
            [
                'name' => 'Выключить диммер',
                'link' => 'off_dimmer.php',
                'count' => 0,
                'system' => 1
            ],
            [
                'name' => 'Увеличить яркость диммера',
                'link' => 'up_dimmer.php',
                'count' => 0,
                'system' => 1
            ],
            [
                'name' => 'Уменьшить яркость диммера',
                'link' => 'down_dimmer.php',
                'count' => 0,
                'system' => 1
            ],
            [
                'name' => 'Установить яркость диммера', // изменение названия этого скрипта влияет на код в DimmerObjectService
                'link' => 'set_dimmer.php',
                'count' => 0,
                'system' => 1
            ],
        ];
    }

    private function getScripts(): array
    {
        $scripts = self::getDimmerScripts();

        $scripts[] = $this->getResetGraphsScript();
        $scripts[] = $this->getCheckCountScript();
        $scripts[] = $this->getResetCountScript();

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
            if (!in_array($script['name'], $this->scripts, true)) {
                $result_scripts[] = $script;
            }
        }

        if (count($result_scripts)) {
            Script::insert($result_scripts);
        }
    }
}
