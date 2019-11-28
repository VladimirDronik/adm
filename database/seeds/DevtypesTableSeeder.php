<?php

use Illuminate\Database\Seeder;
use App\Models\DevType;

class DevtypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $devType = new DevType();
        $devType->name = 'Monoblock 14IN/14OUT';
        $devType->port_numbers = 'in 0 6;out 7 13;in 15 21;out 22 28';
        $devType->save();

        $devType = new DevType();
        $devType->name = 'Mega328';
        $devType->port_numbers = 'in 0 7;out 8 14';
        $devType->save();

        $devType = new DevType();
        $devType->name = 'wifi-in';
        $devType->port_numbers = 'in 0 2';
        $devType->save();

        $devType = new DevType();
        $devType->name = 'wifi-out';
        $devType->port_numbers = 'out 0 2';
        $devType->save();
    }
}
