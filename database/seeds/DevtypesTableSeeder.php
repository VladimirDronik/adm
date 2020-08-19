<?php

use Illuminate\Database\Seeder;
use App\Models\DevType;

class DevtypesTableSeeder extends Seeder
{
    private $devtypes;

    public function __construct()
    {
        $this->devtypes = DevType::pluck('name')->toArray();
    }


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
                'port_numbers' => 'in 0 6;out 7 13;dig 14 14;in 15 21;out 22 28;dig 29 37'
            ],
            [
                'name' => 'MegaD-2561',
                'port_numbers' => 'in 0 13;out 15 28'
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
            [
                'name' => 'Hite-pro',
                'port_numbers' => ''
            ],
        ];

        $result_devtypes = [];

        foreach ($devtypes as $devtype) {
            if (!in_array($devtype['name'], $this->devtypes, true)) {
                $result_devtypes[] = $devtype;
            }
        }

        if (count($result_devtypes)) {
            DevType::insert($result_devtypes);
        }
    }
}
