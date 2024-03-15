<?php

namespace Database\Seeders;

use App\Models\ModbusSlaversType;
use Illuminate\Database\Seeder;

class ModbusSlaversTypesSeeder extends Seeder
{
    private function getTypes(): array
    {
        return [
            [
                'type' => 'wb-led',
                'name' => 'WB-LED',
                'purpose' => 'light',
                'relay' => 0,
            ],
            [
                'type' => 'wb-mir',
                'name' => 'WB-MIR',
                'purpose' => 'ir',
                'relay' => 0,
            ],
            [
                'type' => 'wb-map12e',
                'name' => 'WB-MAP12E',
                'purpose' => 'meter',
                'relay' => 0,
            ],
            [
                'type' => 'wb-mrm2-mini',
                'name' => 'WB-MRM2-mini',
                'purpose' => 'relay',
                'relay' => 1,
            ],
            [
                'type' => 'bcg-301-w',
                'name' => 'Nevoton OPENTHERM',
                'purpose' => 'heat',
                'relay' => 0,
            ],
            [
                'type' => 'ecodim-dali-gw2',
                'name' => 'Ecodim DALI GW2',
                'purpose' => 'light',
                'relay' => 1,
            ]
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slaverTypes = $this->getTypes();

        foreach ($slaverTypes as $slaverType) {
            $type = $slaverType['type'];
            unset($slaverType['type']);

            ModbusSlaversType::updateOrCreate(['type' => $type], $slaverType);
        }
    }
}
