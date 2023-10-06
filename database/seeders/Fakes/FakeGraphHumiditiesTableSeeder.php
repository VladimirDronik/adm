<?php

namespace Database\Seeders\Fakes;

use App\Models\Hygrostat;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakeGraphHumiditiesTableSeeder extends Seeder
{
    const COUNT = 50;

    public function getGraphHumidities()
    {
        $counts = [];

        $date = Carbon::now()->subDays(self::COUNT);
        $hygrostats = Hygrostat::limit(3)->get();

        for ($i = 0; $i < self::COUNT; $i++) {
            foreach ($hygrostats as $hygrostat) {
                $counts[] = [
                    'datetime' => $date->format('Y-m-d H:i:s'),
                    'id_hygrostat' => $hygrostat->id,
                    'value' => rand(0, 100),
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
        DB::table('graph_hygrostats')->insert($this->getGraphHumidities());
    }
}
