<?php

namespace Database\Seeders\Fakes;

use Illuminate\Database\Seeder;
use App\Models\Script;
use Illuminate\Support\Facades\DB;

class FakeScriptsTableSeeder extends Seeder
{
    private $now;

    public function __construct()
    {
        $this->now = date('Y-m-d H:i:s');
    }

    /**
     * Добавляются в таблицу только если она пустая, то есть не сработал основной сидер ScriptsTableSeeder
     *
     * @return array
     */
    public function getScripts(): array
    {
        return [
            [
                'name' => 'Проверка термостата в гостиной',
                'link' => 'termostat_kotel.php',
                'count' => 0,
                'system' => 0
            ],
            [
                'name' => 'Вкл света в прихожей по датчику движения',
                'link' => 'penetration.php',
                'count' => 5,
                'system' => 1
            ],
            [
                'name' => 'Включение обычного режима отопления',
                'link' => 'normal_mode.php',
                'count' => 3,
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
        if (!Script::count()) {
            DB::table('scripts')->insert($this->getScripts());
        }
    }
}
