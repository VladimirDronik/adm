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
        $devtypes = [
            [
                'name' => 'Monoblock 14IN/14OUT',
                'port_numbers' => 'in 0 6;out 7 13;in 15 21;out 22 28'
            ],
            [
                'name' => 'Mega328',
                'port_numbers' => 'in 0 7;out 8 14'
            ],
            [
                'name' => 'wifi-in',
                'port_numbers' => 'in 0 2'
            ],
            [
                'name' => 'wifi-out',
                'port_numbers' => 'out 0 2'
            ],
        ];

        DevType::insert($devtypes);
    }
}
