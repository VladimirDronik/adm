<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nameTypeColors = [
            ['name' => 'blue', 'value' => '#0060aa'],
            ['name' => 'orange', 'value' => '#f36f21'],
            ['name' => 'red', 'value' => '#ff0000'],
            ['name' => 'green', 'value' => '#007439'],
            ['name' => 'purple', 'value' => '#C73C93'],
            ['name' => 'turquoise', 'value' => '#328F9D'],
            ['name' => 'lightGreen', 'value' => '#7EDF44'],
            ['name' => 'yellow', 'value' => '#EEFB4C'],
            ['name' => 'gold', 'value' => '#FFD700'],
        ];

        $hsvTypeColors = [
            ['name' => '', 'value' => '33;48;100'],
            ['name' => '', 'value' => '32;66;100'],
            ['name' => '', 'value' => '34;28;99'],
            ['name' => '', 'value' => '36;33;97'],
            ['name' => '', 'value' => '220;5;98'],
            ['name' => '', 'value' => '0;64;100'],
            ['name' => '', 'value' => '8;55;98'],
            ['name' => '', 'value' => '25;70;100'],
            ['name' => '', 'value' => '40;70;100'],
            ['name' => '', 'value' => '72;97;100'],
            ['name' => '', 'value' => '120;55;90'],
            ['name' => '', 'value' => '160;79;89'],
            ['name' => '', 'value' => '180;81;89'],
            ['name' => '', 'value' => '190;60;100'],
            ['name' => '', 'value' => '225;55;90'],
            ['name' => '', 'value' => '235;10;100'],
            ['name' => '', 'value' => '255;55;89'],
            ['name' => '', 'value' => '270;56;90'],
            ['name' => '', 'value' => '300;69;90'],
            ['name' => '', 'value' => '306;49;91'],
            ['name' => '', 'value' => '344;69;90'],
            ['name' => '', 'value' => '340;45;90'],
        ];

        foreach ($nameTypeColors as $nameTypecolor) {
            Color::updateOrCreate(
                ['name' => $nameTypecolor['name']],
                [
                    'type' => Color::NAME_TYPE,
                    'value' => $nameTypecolor['value'],
                ],
            );
        }

        foreach ($hsvTypeColors as $hsvTypecolor) {
            Color::updateOrCreate(
                ['value' => $hsvTypecolor['value']],
                [
                    'type' => Color::HSV_TYPE,
                    'name' => $hsvTypecolor['name'],
                ],
            );
        }
    }
}
