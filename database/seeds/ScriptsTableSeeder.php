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

    private function getScripts(): array
    {
        $scripts = [
            [
                'name' => 'Очистка графиков',
                'link' => 'reset_graphs.php',
                'count' => 0,
                'system' => 1
            ],
        ];

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
