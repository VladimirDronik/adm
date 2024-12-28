<?php

namespace App\Repositories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;

class RoomRepository
{
    public function getSpecialRooms(): Collection
    {
        return Room::room()
            ->orderBy('group_room')
            ->get();
    }

    public function getRoomGroups(): Collection
    {
        return Room::group()
            ->orderBy('name')
            ->get();
    }

    public function getPaginationGroupsAndSeparateRooms(int $perPage = 30)
    {
        return Room::group()
            ->orWhere(function ($query) {
                $query->room()->whereNull('group_room');
            })
            ->orderBy('sort')
            ->paginate($perPage);
    }

    public function getPaginationGroupRooms(int $groupId, int $perPage = 30)
    {
        return Room::room()
            ->where('group_room', $groupId)
            ->orderBy('sort')
            ->paginate($perPage);
    }

    public function getGroup(int $id): ?Room
    {
        return Room::group()
            ->where('id', $id)
            ->first();
    }

    public function getAllToArray(): array
    {
        return [0 => 'Общие'] +
            $this->getSpecialRooms()
                ->pluck('name', 'id')
                ->toArray();
    }

    public function getAllForViewsToArray(): array
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

    public function getRoomName(mixed $roomId, $rooms = null): string
    {
        if ($roomId === '') {
            return '';
        }

        if ($roomId === '0' || is_null($roomId)) {
            return Room::COMMON_NAME;
        }

        if (empty($rooms)) {
            return optional(Room::find($roomId))->name;
        }

        return optional($rooms->firstWhere('id', $roomId))->name;
    }

    /**
     * Поиск комнаты по названию
     */
    public function getByName(?string $name): ?Room
    {
        return Room::where('name', $name)->first();
    }
}
