<?php

use Illuminate\Database\Seeder;

class FakeScriptsTableSeeder extends Seeder
{
    private $now;

    public function __construct()
    {
        $this->now = date('Y-m-d H:i:s');
    }

    public function getScripts()
    {
        return [
            ['name' => 'Проверка термостата в гостиной', 'link' => 'termostat_kotel.php', 'count' => 0, 'system' => 0],
            ['name' => 'Вкл света в прихожей по датчику движения', 'link' => 'penetration.php', 'count' => 5, 'system' => 1],
            ['name' => 'Включение обычного режима отопления', 'link' => 'normal_mode.php', 'count' => 3, 'system' => 0],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('scripts')->insert($this->getScripts());
        } catch (\Throwable $e) {

        }
    }
}
