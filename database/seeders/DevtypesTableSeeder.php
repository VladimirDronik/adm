<?php

namespace Database\Seeders;

use App\Models\DevType;
use Illuminate\Database\Seeder;

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
                'port_numbers' => 'in 0 6;out 7 13;dig 14 14;in 15 21;out 22 28;dig 29 45',
            ],
            [
                'name' => 'MegaD-2561',
                'port_numbers' => 'in 0 13;out 15 28',
            ],
            [
                'name' => 'Mega328',
                'port_numbers' => 'in 0 7;out 8 14',
            ],
            [
                'name' => 'ModbusTCP',
                'port_numbers' => '',
            ],
        ];

        foreach ($devtypes as $devtype) {
            DevType::updateOrCreate(
                ['name' => $devtype['name']],
                ['port_numbers' => $devtype['port_numbers']]
            );
        }
    }
}
