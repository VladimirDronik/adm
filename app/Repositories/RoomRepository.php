<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository
{
    public function getSpecialRooms()
    {
        return Room::where('id','>',0)->orderBy('sort')->get();
    }

    public function getPaginationSpecialRooms($pagination_count = 10)
    {
        return Room::where('id','>',0)->orderBy('sort')->paginate($pagination_count);
    }

    public function getAllToArray()
    {
        return [0 => Room::COMMON_NAME] + $this->getSpecialRooms()->pluck('name', 'id')->toArray();
    }

    public function getRoomName($room_id, $rooms = null)
    {
        if ($room_id === '') {
            return '';
        }

        if ($room_id === '0') {
            return Room::COMMON_NAME;
        }

        if (empty($rooms)) {
            return optional(Room::find($room_id))->name;
        }

        return optional($rooms->firstWhere('id', $room_id))->name;
    }
}