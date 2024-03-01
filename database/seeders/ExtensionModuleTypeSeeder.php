<?php

namespace Database\Seeders;

use App\Models\ExtensionModuleType;
use Illuminate\Database\Seeder;

class ExtensionModuleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $extensionModuleTypes = [
            [
                'name' => '0-10V',
                'ports' => '0..10V 0 7;',
            ],
            [
                'name' => 'MegaD-16R-XT',
                'ports' => 'out 0 15;',
            ],
            [
                'name' => 'MegaD-16I-XT',
                'ports' => 'in 0 15;',
            ],
        ];

        foreach ($extensionModuleTypes as $extensionModuleType) {
            ExtensionModuleType::updateOrCreate(
                ['name' => $extensionModuleType['name']],
                ['ports' => $extensionModuleType['ports']]
            );
        }
    }
}
