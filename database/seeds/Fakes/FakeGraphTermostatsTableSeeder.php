<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Termostat;

class FakeGraphTermostatsTableSeeder extends Seeder
{
    const COUNT = 2000;

    public function getGraphTermostats()
    {
        $termostats = Termostat::limit(2)->get();
        $graph_termostats = [];

        $date = Carbon::now()->subDays( (int) (self::COUNT / 290));

        for ($i = 0; $i < self::COUNT; $i++) {
            foreach ($termostats as $termostat) {
                $graph_termostats[] = [
                    'datetime' => $date->format('Y-m-d H:i:s'),
                    'id_termostat' => $termostat->id,
                    'value' => rand(20, 25).'.'.rand(10, 99)
                ];
            }
            $date->addMinutes(5);
        }

        return $graph_termostats;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('graph_termostats')->insert($this->getGraphTermostats());
    }
}
