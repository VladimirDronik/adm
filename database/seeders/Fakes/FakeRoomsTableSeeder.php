<?php

namespace Database\Seeders\Fakes;

use App\Models\Color;
use App\Models\Room;
use Illuminate\Database\Seeder;

class FakeRoomsTableSeeder extends Seeder
{
    const GROUP_COUNT = 4;

    private $images;

    private $colors;

    private $room_names;

    public function __construct()
    {
        $this->images = ['1et_.svg', '2et_.svg', 'kuhn.png', 'ulica.svg'];
        $this->colors = Color::where('type', Color::NAME_TYPE)->get()->pluck('name')->toArray();
        $this->room_names = ['Кухня', 'Гостиная', 'Детская', 'Ванная',
            'Прихожая', 'Чердак', 'Подвал', 'Балкон'];
    }

    private function getRandColor(): string
    {
        return $this->colors[rand(0, count($this->colors) - 1)];
    }

    private function getRandImage(): string
    {
        return $this->images[rand(0, count($this->images) - 1)];
    }

    private function getGroups(): array
    {
        $groups = [];

        for ($i = 0; $i < self::GROUP_COUNT; $i++) {
            $groups[] = [
                'name' => ($i + 1).'-й этаж',
                'image' => $this->getRandImage(),
                'style' => $this->getRandColor(),
                'sort' => $i + 1,
                'is_group' => 1,
            ];
        }

        return $groups;
    }

    private function getRooms(): array
    {
        $groups = Room::group()->get();
        $rooms = [];

        // Комнаты в группах
        foreach ($groups as $group) {
            if (rand(0, 10) > 7) {
                continue;
            }

            $count = rand(2, count($this->room_names));
            for ($i = 0; $i < $count; $i++) {
                $rooms[] = [
                    'name' => $group->name.' '.$this->room_names[$i],
                    'image' => $this->getRandImage(),
                    'style' => $this->getRandColor(),
                    'sort' => $i + 1,
                    'is_group' => 0,
                    'group_room' => $group->id,
                ];
            }
        }

        // Комнаты без группы

        $count = rand(2, count($this->room_names));
        for ($i = 0; $i < $count; $i++) {
            $rooms[] = [
                'name' => $this->room_names[$i],
                'image' => $this->getRandImage(),
                'style' => $this->getRandColor(),
                'sort' => count($groups) + $i + 1,
                'is_group' => 0,
                'group_room' => null,
            ];
        }

        return $rooms;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room::insert($this->getGroups());
        Room::insert($this->getRooms());
    }
}
