<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FakeGraphCountsTableSeeder extends Seeder
{
    const COUNT = 500;

    public function getGraphCounts()
    {
        $counts = [];

        $date = Carbon::now()->subDays(self::COUNT);

        for ($i = 0; $i < self::COUNT; $i++) {
            for ($id_count = 1; $id_count <= 3; $id_count++) {
                $counts[] = [
                    'date' => $date->format('Y-m-d'),
                    'id_count' => $id_count,
                    'value' => rand(2000, 6000)
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
        try {
            DB::table('graph_counts')->insert($this->getGraphCounts());
        } catch (\Throwable $e) {

        }
    }
}
