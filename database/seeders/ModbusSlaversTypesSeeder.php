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
            ],
            [
                'type' => 'wb-mir',
                'name' => 'WB-MIR',
            ],
            [
                'type' => 'wb-map12e',
                'name' => 'WB-MAP12E',
            ],
            [
                'type' => 'wb-mrm2-mini',
                'name' => 'WB-MRM2-mini',
            ],
            [
                'type' => 'bcg-301-w',
                'name' => 'Nevoton OPENTHERM',
            ],
            [
                'type' => 'ecodim-dali-gw2',
                'name' => 'Ecodim DALI GW2',
            ]
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = $this->getTypes();

        foreach ($types as $type) {
            ModbusSlaversType::updateOrCreate(
                ['type' => $type['type']],
                ['name' => $type['name']]
            );
        }
    }
}
