<?php

namespace Tests\TestData;

use App\Models\Room;

class RoomsData
{
    /**
     * Генератор сущностей для комнаты
     */
    public function generateRoom(): array
    {
        $roomGroup = Room::create([
            'name' => 'Тестовая группа комнат',
            'image' => 'noimage.png',
            'style' => 'grey',
            'sort' => 1,
            'is_group' => 1,
        ]);

        $room = Room::create([
            'name' => 'Тестовая комната',
            'image' => 'noimage.png',
            'style' => 'grey',
            'sort' => 1,
            'is_group' => 0,
            'group_room' => $roomGroup->id,
        ]);

        return [
            'room_group' => $roomGroup,
            'room' => $room,
        ];
    }
}
