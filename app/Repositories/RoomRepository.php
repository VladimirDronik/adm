<?php

namespace App\Repositories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Model;

class RoomRepository
{
    public function getSpecialRooms()
    {
        return Room::room()
            ->orderBy('group_room')
            ->get();
    }

    public function getRoomGroups()
    {
        return Room::group()
            ->orderBy('name')
            ->get();
    }

    public function getPaginationGroupsAndSeparateRooms(int $pagination_count = 30)
    {
        return Room::group()
            ->orWhere(function ($query) {
                $query->room()->whereNull('group_room');
            })
            ->orderBy('sort')
            ->paginate($pagination_count);
    }

    public function getPaginationGroupRooms(int $groupId, int $pagination_count = 30)
    {
        return Room::room()
            ->where('group_room', $groupId)
            ->orderBy('sort')
            ->paginate($pagination_count);
    }

    public function getGroup($id)
    {
        return Room::group()
            ->where('id', $id)
            ->first();
    }

    public function getAllToArray(): array
    {
        return [0 => Room::COMMON_NAME] +
            $this->getSpecialRooms()
                ->pluck('name', 'id')
                ->toArray();
    }

    public function getAllWithoutCommonToArray(): array
    {
        return $this->getSpecialRooms()
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getRoomName($room_id, $rooms = null): string
    {
        if ($room_id === '') {
            return '';
        }

        if ($room_id === '0' || is_null($room_id)) {
            return Room::COMMON_NAME;
        }

        if (empty($rooms)) {
            return optional(Room::find($room_id))->name;
        }

        return optional($rooms->firstWhere('id', $room_id))->name;
    }

    /**
     * Поиск комнаты по названию
     */
    public function getByName(?string $name): ?Model
    {
        return Room::where('name', $name)->first();
    }
}
