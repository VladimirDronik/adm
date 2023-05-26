<?php

use Illuminate\Database\Seeder;
use App\Models\ExtensionModuleType;

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
                'ports' => 'in 0 6;out 7 13;dig 14 14;in 15 21;out 22 28;dig 29 37'
            ],
        ];

        $result_extensionModuleTypes = [];

        foreach ($extensionModuleTypes as $extensionModuleType) {
            if (!in_array($extensionModuleType['name'], $this->extensionModuleTypes, true)) {
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
