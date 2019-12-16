<?php

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\HomeObject;
use App\Models\Scene;
use App\Models\View;
use Faker\Factory;

class FakeViewItemsTableSeeder extends Seeder
{
    const COUNT = 20;

    public function getViewItems()
    {
        $rooms = Room::all();
        $objects = HomeObject::whereHas('methods')->with('methods')->get();
        $scenes = Scene::all();
        $typeNames = View::getTypeNameIds();
        $faker = Factory::create();

        $views = [];

        for ($i = 0; $i < self::COUNT; $i++) {
            $typeName = $typeNames[rand(0, count($typeNames)-1)];
            $object = rand(0, 10) > 4 ? $objects[rand(0, count($objects)-1)] : null;
            $left = rand(0, 10) > 6 ? null : rand(50, 80);
            $top = is_null($left) ? null : rand(50, 90);
            $views[] = [
                'type' => $typeName,
                'description' => $faker->sentence,
                'status' => rand(0, 10) > 6 ? 'off' : 'on',
                'id_object' => is_null($object) ? null : $object->id,
                'id_method' => is_null($object) ? null : $object->methods[rand(0, count($object->methods)-1)]->id,
                'icon' => rand(0, 10) > 6 ? 'lamp' : 'noimage',
                'title' => 'стол<br>свет вкл',
                'position_left' => $left,
                'position_top' => $top,
                'room' => rand(0, 10) > 6 ? null : $rooms[rand(0, count($rooms)-1)]->id,
                'scene' => rand(0, 10) > 6 ? null : $scenes[rand(0, count($scenes)-1)]->id,
                'sort' => $i + 1,
                'active' => rand(0, 10) > 6 ? 0 : 1
            ];
        }

        return $views;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('view_items')->insert($this->getViewItems());
    }
}
