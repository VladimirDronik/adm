<?php

use Illuminate\Database\Seeder;

class FakeMethodsTableSeeder extends Seeder
{
    public function getMethods()
    {
        return [];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('methods')->insert($this->getMethods());
        } catch (\Throwable $e) {

        }
    }
}
