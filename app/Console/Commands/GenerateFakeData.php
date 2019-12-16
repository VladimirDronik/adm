<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class GenerateFakeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill database by test data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tables = ['Users', 'Objects', 'Scripts', 'Devices', 'Dimmies',
            'Rooms', 'Methods', 'Termostats', 'Scenes', 'Counts', 'ViewItems', 'SchedulerTasks',
            'GraphCounts', 'GraphTermostats', 'GraphLights', 'GraphHumidities', 'Logs'];

        foreach ($tables as $table) {
            Artisan::call('db:seed', ['--class' => 'Fake'.$table.'TableSeeder']);
            $this->info('Fake '.$table.' - done!');
        }
    }
}
