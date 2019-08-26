<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository
{
    public function getSpecialRooms()
    {
        return Room::where('id','>',0)->orderBy('sort')->get();
    }

    public function getRoomName($room_id, $rooms)
    {
        if ($room_id === '') {
            return '';
        }

        if ($room_id === '0') {
            return Room::COMMON_NAME;
        }

        return optional($rooms->firstWhere('id', $room_id))->name;
    }
}