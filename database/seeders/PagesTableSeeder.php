<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    private $pages;

    public function __construct()
    {
        $this->pages = Page::pluck('name')->toArray();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = [
            [
                'name' => 'Котёл отопления',
                'type' => '2field',
                'link' => 'kotel',
                'sort' => 1,
            ],
            [
                'name' => 'Бойлер ГВС',
                'type' => '2field',
                'link' => 'boiler',
                'sort' => 1,
            ],
            [
                'name' => 'Теплый пол',
                'type' => '2field',
                'link' => 'tpol',
                'sort' => 1,
            ],
            [
                'name' => 'Насосное оборудование',
                'type' => '2field',
                'link' => 'nasos',
                'sort' => 1,
            ],
            [
                'name' => 'Электрооборудование',
                'type' => '2field',
                'link' => 'electro',
                'sort' => 1,
            ],
            [
                'name' => 'Вентиляция',
                'type' => '2field',
                'link' => 'vent',
                'sort' => 1,
            ],
        ];

        $result_page = [];

        foreach ($page as $p) {
            if (! in_array($p['name'], $this->pages, true)) {
                $result_page[] = $p;
            }
        }

        if (count($result_page)) {
            Page::insert($result_page);
        }
    }
}
