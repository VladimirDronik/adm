<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $db_colors = Color::pluck('name')->toArray();
        $colors = Color::getColors(false);

        $new_colors = [];
        foreach ($colors as $color) {
            if (!in_array($color, $db_colors, true)) {
                $new_colors[] = ['name' => $color];
            }
        }

        if (count($new_colors)) {
            Color::insert($new_colors);
        }
    }
}
