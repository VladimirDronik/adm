<?php

namespace Database\Seeders;

use App\Models\ExtensionModuleType;
use Illuminate\Database\Seeder;

class ExtensionModuleTypeSeeder extends Seeder
{
    private $extensionModuleTypes;

    public function __construct()
    {
        $this->extensionModuleTypes = ExtensionModuleType::pluck('name')->toArray();
    }

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
        ];

        $result_extensionModuleTypes = [];

        foreach ($extensionModuleTypes as $extensionModuleType) {
            if (! in_array($extensionModuleType['name'], $this->extensionModuleTypes, true)) {
                $result_extensionModuleTypes[] = $extensionModuleType;
            }
        }

        if (count($result_extensionModuleTypes)) {
            foreach ($result_extensionModuleTypes as $result_extensionModuleType) {
                ExtensionModuleType::create([
                    'name' => $result_extensionModuleType['name'],
                    'ports' => $result_extensionModuleType['ports'],
                ]);
            }
        }
    }
}
