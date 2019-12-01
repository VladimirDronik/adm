<?php

namespace App\Repositories;

use App\Models\View;

class ViewRepository {

    public function getAll()
    {
        return View::with('eroom','escene')->orderBy('id')->get();
    }

    public function getAllToArray()
    {
        $views = View::select('id','name')->orderBy('name')->pluck('name', 'id')->toArray();
        array_walk($views, function (&$view, $key) { $view = $key.' - '.$view; });

        return $views;
    }

    public function getByRoom($room_id, $pagination_count = 30)
    {
        $query = View::with('eroom', 'escene', 'eobject', 'emethod');

        if ($room_id === '0') {
            $query->whereNull('room');
        } elseif (!is_null($room_id)) {
            $query->where('room', $room_id);
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }

    public function updateObject(array $data)
    {
        if (empty($data['id_object'])) {
            View::where('id', $data['id_view'])->update(['id_object' => null, 'id_method' => null]);
        } else {
            View::where('id', $data['id_view'])->update(['id_object' => $data['id_object']]);
        }
    }

    public function updateMethod(array $data)
    {
        if (empty($data['id_method'])) {
            View::where('id', $data['id_view'])->update(['id_method' => null]);
        } else {
            View::where('id', $data['id_view'])->update(['id_method' => $data['id_method']]);
        }
    }
}