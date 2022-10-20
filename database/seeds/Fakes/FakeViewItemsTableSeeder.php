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
        $rooms = Room::room()->get();
        $objects = HomeObject::whereHas('methods')->with('methods')->get();
        $scenes = Scene::all();
        $typeNames = View::getTypeIds();
        $faker = Factory::create();

        $views = [];

        $common_sort = 0;

        for ($i = 0; $i < count($rooms) / 2; $i++) {

            $typeName = $typeNames[rand(0, count($typeNames)-1)];
            $count = rand(1, 5);
            $room = rand(0, 10) > 6 ? null : $rooms[$i];
            $room_group = !is_null($room)
                ? (is_null($room->group_room) ? $room->id : $room->group_room)
                : null;

            for ($j = 0; $j < $count; $j++) {
                $object = rand(0, 10) > 4 ? $objects[rand(0, count($objects)-1)] : null;
                $left = rand(0, 10) > 6 ? null : rand(50, 80);
                $top = is_null($left) ? null : rand(50, 90);

                if (is_null($room)) {
                    $common_sort++;
                }

                $views[] = [
                    'type' => $typeName,
                    'description' => $faker->sentence,
                    'status' => rand(0, 10) > 6 ? 'off' : 'on',
                    'id_object' => is_null($object) ? null : $object->id,
                    'on_method' => is_null($object) ? null
                        : $object->methods[rand(0, count($object->methods)-1)]->id,
                    'off_method' => (is_null($object) || $typeName !== View::TYPE_SWITCH) ? null
                        : $object->methods[rand(0, count($object->methods)-1)]->id,
                    'icon' => rand(0, 10) > 6 ? 'lamp' : 'noimage',
                    'title' => 'стол<br>свет вкл',
                    'position_left' => $left,
                    'position_top' => $top,
                    'room' => is_null($room) ? null : $room->id,
                    'room_group' => $room_group,
                    'scene' => rand(0, 10) > 6 ? null : $scenes[rand(0, count($scenes)-1)]->id,
                    'sort' =>  is_null($room) ? $common_sort : $j + 1,
                    'active' => rand(0, 10) > 6 ? 0 : 1
                ];
            }
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
