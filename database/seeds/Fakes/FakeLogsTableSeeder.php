<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory;

class FakeLogsTableSeeder extends Seeder
{
    const COUNT = 1000;

    public function getLogs()
    {
        $date = Carbon::now()->subDays(self::COUNT);
        $faker = Factory::create();

        $types = ['system', 'socket', 'server'];

        $logs = [];

        for ($i = 0; $i < self::COUNT; $i++) {
            $logs[] = [
                'date' => $date->format('Y-m-d H:i:s'),
                'type' => $types[rand(0, count($types)-1)],
                'message' => $faker->sentence
            ];

            $date->addDay();
            $date->hour = rand(0, 23);
            $date->minute = rand(0, 59);
            $date->second = rand(0, 59);
        }

        return $logs;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('logs')->insert($this->getLogs());
        } catch (\Throwable $e) {

        }
    }
}
