<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FakeGraphHumiditiesTableSeeder extends Seeder
{
    const COUNT = 500;

    public function getGraphHumidities()
    {
        $counts = [];

        $date = Carbon::now()->subDays(self::COUNT);

        for ($i = 0; $i < self::COUNT; $i++) {
            for ($id_count = 1; $id_count <= 3; $id_count++) {
                $counts[] = [
                    'datetime' => $date->format('Y-m-d H:i:s'),
                    'id_count' => $id_count,
                    'value' => rand(0, 100)
                ];
            }
            $date->addDay();
        }

        return $counts;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('graph_humidities')->insert($this->getGraphHumidities());
    }
}
