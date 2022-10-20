<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuTableSeeder extends Seeder
{
    private $menu;

    public function __construct()
    {
        $this->menu = Menu::pluck('name')->toArray();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = [
            [
                'name' => 'Инженерное',
                'title' => 'Инженерное',
                'link' => 'ing',
                'image' => 'ing',
                'parent' => 0,
                'sort' => 1,
                'active' => 1
            ],
            [
                'name' => 'Котёл',
                'title' => 'Котёл',
                'link' => 'kotel',
                'image' => 'kotel',
                'parent' => 1,
                'sort' => 1,
                'active' => 1
            ],
            [
                'name' => 'Бойлер ГВС',
                'title' => 'Бойлер ГВС',
                'link' => 'boiler',
                'image' => 'boiler',
                'parent' => 1,
                'sort' => 2,
                'active' => 1
            ],
            [
                'name' => 'Теплый пол',
                'title' => 'Теплый пол',
                'link' => 'tpol',
                'image' => 'tpol',
                'parent' => 1,
                'sort' => 3,
                'active' => 1
            ],
            [
                'name' => 'Насосы',
                'title' => 'Насосы',
                'link' => 'nasos',
                'image' => 'nasos',
                'parent' => 1,
                'sort' => 4,
                'active' => 1
            ],
            [
                'name' => 'Электрооборудование',
                'title' => 'Электрооборудование',
                'link' => 'electro',
                'image' => 'electro',
                'parent' => 1,
                'sort' => 5,
                'active' => 1
            ],
            [
                'name' => 'Вентиляция',
                'title' => 'Вентиляция',
                'link' => 'vent',
                'image' => 'vent',
                'parent' => 1,
                'sort' => 6,
                'active' => 1
            ],
        ];

        $result_menu = [];

        foreach ($menu as $m) {
            if (!in_array($m['name'], $this->menu, true)) {
                $result_menu[] = $m;
            }
        }

        if (count($result_menu)) {
            Menu::insert($result_menu);
        }
    }
}
