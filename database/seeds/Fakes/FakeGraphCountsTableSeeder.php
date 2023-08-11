<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Count;

class FakeGraphCountsTableSeeder extends Seeder
{
    const COUNT = 50;

    public function getGraphCounts()
    {
        $counts = [];

        $date = Carbon::now()->subDays(self::COUNT);
        $cs = Count::limit(2)->get();

        for ($i = 0; $i < self::COUNT; $i++) {
            foreach ($cs as $count) {
                $counts[] = [
                    'datetime' => $date->format('Y-m-d'),
                    'id_count' => $count->id,
                    'value' => rand(2000, 6000).'.'.rand(10,90)
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
