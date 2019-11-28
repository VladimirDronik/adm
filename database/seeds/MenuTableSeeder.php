<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = [
            [
                'name' => 'dashboard',
                'title' => 'Избранное',
                'link' => '/app/dashboard',
                'image' => 'favorites.svg',
                'sort' => 1,
                'active' => 1
            ],
            [
                'name' => 'lighting',
                'title' => 'Освещение',
                'link' => '',
                'image' => 'light.svg',
                'sort' => 3,
                'active' => 0
            ],
            [
                'name' => 'temperature',
                'title' => 'Температура',
                'link' => '/app/temperature',
                'image' => 'temperatura.svg',
                'sort' => 2,
                'active' => 1
            ],
            [
                'name' => 'graphics',
                'title' => 'Графики',
                'link' => '',
                'image' => 'graphics.svg',
                'sort' => 4,
                'active' => 1
            ],
            [
                'name' => 'events',
                'title' => 'События',
                'link' => '/app/events',
                'image' => 'events.svg',
                'sort' => 5,
                'active' => 0
            ],
            [
                'name' => 'settings',
                'title' => 'Настройки',
                'link' => '/app/settings',
                'image' => 'events.svg',
                'sort' => 6,
                'active' => 0
            ],
        ];

        Menu::insert($menu);
    }
}
