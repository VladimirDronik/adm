<?php

use Illuminate\Database\Seeder;
use App\Models\HomeObject;
use App\Models\Room;

class FakeTermostatsTableSeeder extends Seeder
{
    const COUNT = 5;

    private $rooms;
    private $objects;

    public function __construct()
    {
        $this->rooms = Room::all();
        $this->objects = HomeObject::with('methods')->whereHas('methods')->get();
        if (!count($this->objects)) {
            throw new Exception('Termostats seeder: Objects which methods not found');
        }
    }

    public function getRandRoomId()
    {
        if (rand(0, 10) > 7) {
            return null;
        }

        return $this->rooms[rand(0, count($this->rooms)-1)]->id;
    }

    public function getRandObject()
    {
        return $this->objects[rand(0, count($this->objects)-1)];
    }

    public function getRandObjectMethodId($object)
    {
        return $object->methods[rand(0, count($object->methods)-1)]->id;
    }

    public function getTermostats()
    {
        $termostats = [];

        for ($i = 0; $i < self::COUNT; $i++) {

            $object = $this->getRandObject();

            $termostats[] = [
                'id_object' => $this->getRandObject()->id,
                'current' => rand(20, 30).'.'.rand(10, 99),
                'optimal' => rand(20, 30),
                'gisteresis' => 1,
                'thermostat' => rand(0, 1),
                'object' => $object->id,
                'method_on' => $this->getRandObjectMethodId($object),
                'method_off' => $this->getRandObjectMethodId($object),
                'id_termometr' => strtolower(str_random(12)),
                'min_threshold' => rand(10, 12),
                'max_threshold' => rand(19, 25),
                'min_alarm' => rand(3, 7),
                'max_alarm' => rand(28, 35),
                'room' => $this->getRandRoomId()
            ];
        }

        return $termostats;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('termostats')->insert($this->getTermostats());
    }
}
