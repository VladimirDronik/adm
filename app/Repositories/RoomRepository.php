<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository {

    public function getAll()
    {
        return Room::where('id','>','0')->orderBy('sort', 'ASC')->get();
    }
}