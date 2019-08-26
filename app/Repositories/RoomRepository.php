<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository {

    public function getAll()
    {
        return Room::orderBy('sort')->get();
    }
}