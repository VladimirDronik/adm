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

    private function getScripts(): array
    {
        return [
            [
                'name' => 'Системный скрипт',
                'link' => 'system_script.php',
                'count' => 0,
                'system' => 1
            ],
            [
                'name' => 'Скрипт',
                'link' => 'script.php',
                'count' => 0,
                'system' => 0
            ],
        ];
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
