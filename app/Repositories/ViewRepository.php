<?php

namespace App\Repositories;

use App\Models\View;

class ViewRepository {

    public function getAll()
    {
        return View::orderBy('id')->get();
    }

    public function getByRoom($room_id, $pagination_count = 30)
    {
        $query = View::query();

        if (!is_null($room_id)) {
            $query->where('room',$room_id);
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }
}