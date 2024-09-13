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
                'name' => 'Nevoton Opentherm',
                'purpose' => 'heat',
                'relay' => 0,
            ],
            [
                'type' => 'ecodim-dali-gw2',
                'name' => 'Ecodim DALI GW2',
                'purpose' => 'light',
                'relay' => 1,
            ],
            [
                'type' => 'onokom-gr-1-mb-b',
                'name' => 'ONOKOM GR-1-MB-B',
                'purpose' => 'ac',
                'relay' => 0,
            ],
            [
                'type' => 'onokom-gr-3-mb-b',
                'name' => 'ONOKOM GR-3-MB-B',
                'purpose' => 'ac',
                'relay' => 0,
            ],
            [
                'type' => 'onokom-tcl-1-mb-b',
                'name' => 'ONOKOM TCL-1-MB-B',
                'purpose' => 'ac',
                'relay' => 0,
            ],
            [
                'type' => 'onokom-dk-1-mb-b',
                'name' => 'ONOKOM DK-1-MB-B',
                'purpose' => 'ac',
                'relay' => 0,
            ],
            [
                'type' => 'onokom-aux-1-mb-b',
                'name' => 'ONOKOM AUX-1-MB-B',
                'purpose' => 'ac',
                'relay' => 0,
            ],
            [
                'type' => 'onokom-me-1-mb-b',
                'name' => 'ONOKOM ME-1-MB-B',
                'purpose' => 'ac',
                'relay' => 0,
            ],
            [
                'type' => 'onokom-hs-3-mb-b',
                'name' => 'ONOKOM HS-3-MB-B',
                'purpose' => 'ac',
                'relay' => 0,
            ],
            [
                'type' => 'onokom-hr-1-mb-b',
                'name' => 'ONOKOM HR-1-MB-B',
                'purpose' => 'ac',
                'relay' => 0,
            ],
            [
                'type' => 'onokom-hs-6-mb-b',
                'name' => 'ONOKOM HS-6-MB-B',
                'purpose' => 'ac',
                'relay' => 0,
            ],
            [
                'type' => 'onokom-mh-8-mb-b',
                'name' => 'ONOKOM MH-8-MB-B',
                'purpose' => 'ac',
                'relay' => 0,
            ],
            [
                'type' => 'lessar-mu-1-01',
                'name' => 'LESSAR MU-1-01',
                'purpose' => 'ac',
                'relay' => 0,
            ],
            [
                'type' => 'beg-311-w',
                'name' => 'Nevoton Ebus',
                'purpose' => 'heat',
                'relay' => 0,
            ],
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
