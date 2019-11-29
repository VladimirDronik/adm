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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('db:seed', ['--class' => 'FakeUsersTableSeeder']);
        $this->info('fake admin - done!');
        Artisan::call('db:seed', ['--class' => 'FakeObjectsTableSeeder']);
        $this->info('fake objects - done!');
        Artisan::call('db:seed', ['--class' => 'FakeScriptsTableSeeder']);
        $this->info('fake scripts - done!');
        Artisan::call('db:seed', ['--class' => 'FakeDevicesTableSeeder']);
        $this->info('fake devices - done!');
        Artisan::call('db:seed', ['--class' => 'FakeRoomsTableSeeder']);
        $this->info('fake rooms - done!');
        Artisan::call('db:seed', ['--class' => 'FakeMethodsTableSeeder']);
        $this->info('fake methods - done!');
        Artisan::call('db:seed', ['--class' => 'FakeTermostatsTableSeeder']);
        $this->info('fake termostats - done!');
        Artisan::call('db:seed', ['--class' => 'FakeScenesTableSeeder']);
        $this->info('fake scenes - done!');
        Artisan::call('db:seed', ['--class' => 'FakeCountsTableSeeder']);
        $this->info('fake counts - done!');
        Artisan::call('db:seed', ['--class' => 'FakeGraphCountsTableSeeder']);
        $this->info('fake graph counts - done!');
        Artisan::call('db:seed', ['--class' => 'FakeGraphTermostatsTableSeeder']);
        $this->info('fake graph termostats - done!');
        Artisan::call('db:seed', ['--class' => 'FakeGraphLightsTableSeeder']);
        $this->info('fake graph lights - done!');
        Artisan::call('db:seed', ['--class' => 'FakeGraphHumiditiesTableSeeder']);
        $this->info('fake graph humidities - done!');
        Artisan::call('db:seed', ['--class' => 'FakeLogsTableSeeder']);
        $this->info('fake logs - done!');
    }
}
