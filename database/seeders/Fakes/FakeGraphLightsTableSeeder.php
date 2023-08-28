<?php

namespace Database\Seeders\Fakes;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FakeGraphLightsTableSeeder extends Seeder
{
    const COUNT = 50;

    public function getGraphLights()
    {
        $counts = [];

        $date = Carbon::now()->subDays(self::COUNT);

        for ($i = 0; $i < self::COUNT; $i++) {
            for ($id_count = 1; $id_count <= 3; $id_count++) {
                $counts[] = [
                    'datetime' => $date->format('Y-m-d H:i:s'),
                    'id_count' => $id_count,
                    'value' => rand(20, 25).'.'.rand(10,99)
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
        DB::table('graph_lights')->insert($this->getGraphLights());
    }
}
