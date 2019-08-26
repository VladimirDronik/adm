<?php

namespace App\Repositories;

use App\Models\View;

class ViewRepository {

    public function getAll()
    {
        return View::with('eroom','escene')->orderBy('id')->get();
    }

    public function getByRoom($room_id, $pagination_count = 5)
    {
        $query = View::with('eroom','escene');

        if (!is_null($room_id)) {
            $query->where('room',$room_id);
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }
}