<?php

use Illuminate\Database\Seeder;

class FakeRoomsTableSeeder extends Seeder
{
    public function getRooms()
    {
        return [
            [
                'name' => 'Весь дом',
                'image' => 'noimage.png',
                'style' => 'orange',
                'sort' => 1
            ],
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
        //try {
            DB::statement("SET SESSION sql_mode='NO_AUTO_VALUE_ON_ZERO';");
            DB::statement("ALTER TABLE rooms AUTO_INCREMENT = 0;");
            DB::table('rooms')->insert($this->getRooms());
       // } catch (\Throwable $e) {

       // }
    }
}
