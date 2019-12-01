<?php

use Illuminate\Database\Seeder;

class FakeRoomsTableSeeder extends Seeder
{
    public function getRooms()
    {
        return [
            [
                'name' => '1-й этаж',
                'image' => '1et_.svg',
                'style' => 'blue',
                'sort' => 4
            ],
            [
                'name' => '2-й этаж',
                'image' => '2et_.svg',
                'style' => 'green',
                'sort' => 3
            ],
            [
                'name' => 'Гостиная',
                'image' => 'kuhn.png',
                'style' => 'blue',
                'sort' => 2
            ],
            [
                'name' => 'Улица',
                'image' => 'ulica.svg',
                'style' => 'red',
                'sort' => 5
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms')->insert($this->getRooms());
    }
}
