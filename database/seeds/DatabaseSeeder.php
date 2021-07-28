<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ColorsTableSeeder::class);
        $this->call(DevtypesTableSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(ScriptsTableSeeder::class);
        $this->call(ObjtypesTableSeeder::class);
        $this->call(LogsSettingsTableSeeder::class);
        $this->call(NotifsettingsTableSeeder::class);
        $this->call(ShedullerTableSeeder::class);
        //$this->call(PagesTableSeeder::class); //убрано создание дефолтных страниц
        $this->call(ElementsTableSeeder::class);
    }
}
