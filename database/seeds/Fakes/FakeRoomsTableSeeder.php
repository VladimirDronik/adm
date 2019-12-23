<?php

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Color;

class FakeRoomsTableSeeder extends Seeder
{
    private $images;
    private $colors;
    private $room_names;

    public function __construct()
    {
        $this->images = ['1et_.svg', '2et_.svg', 'kuhn.png', 'ulica.svg'];
        $this->colors = Color::getColors(false);
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
        return [
            [
                'name' => '1-й этаж',
                'image' => '1et_.svg',
                'style' => 'blue',
                'sort' => 3,
                'is_group' => 1
            ],
            [
                'name' => '2-й этаж',
                'image' => '2et_.svg',
                'style' => 'green',
                'sort' => 2,
                'is_group' => 1
            ],
            [
                'name' => '3-й этаж',
                'image' => 'kuhn.png',
                'style' => 'blue',
                'sort' => 1,
                'is_group' => 1
            ],
            [
                'name' => 'Улица',
                'image' => 'ulica.svg',
                'style' => 'red',
                'sort' => 4,
                'is_group' => 1
            ],
        ];
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
                    'name' => $this->room_names[$i],
                    'image' => $this->getRandImage(),
                    'style' => $this->getRandColor(),
                    'sort' => $i + 1,
                    'is_group' => 0,
                    'group_room' => $group->id
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
                'group_room' => null
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
