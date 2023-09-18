<?php

namespace App\Repositories;

use App\Models\View;

class ViewRepository
{
    public function getAll()
    {
        return View::with('eroom', 'escene')->orderBy('id')->get();
    }

    public function getAllToArray()
    {
        $views = View::select('id', 'description')->orderBy('description')
            ->pluck('description', 'id')->toArray();

        //array_walk($views, function (&$view, $key) { $view = $key.' - '.$view; });

        return $views;
    }

    public function getByRoom($room_id, $pagination_count = 50)
    {
        $query = View::with('eroom', 'escene', 'eobject', 'emethod');

        if ($room_id === '0') {
            $query->whereNull('room')->orderBy('sort');
        } elseif (! is_null($room_id)) {
            $query->where('room', $room_id)->orderBy('sort');
        } else {
            $query->orderBy('id');
        }

        return $query->paginate($pagination_count);
    }

    public function updateObject(array $data)
    {
        if (empty($data['id_object'])) {
            View::where('id', $data['id_view'])->update(['id_object' => null, 'on_method' => null,
                'off_method' => null, 'on_method_params' => null, 'off_method_params' => null]);
        } else {
            View::where('id', $data['id_view'])->update(['id_object' => $data['id_object']]);
        }
    }

    public function updateMethod(array $data)
    {
        if (empty($data['id_method'])) {
            View::where('id', $data['id_view'])
                ->update(['on_method' => null, 'on_method_params' => null]);
        } else {
            if (empty($data['params'])) {
                View::where('id', $data['id_view'])
                    ->update(['on_method' => $data['id_method'], 'on_method_params' => null]);
            } else {
                View::where('id', $data['id_view'])
                    ->update(['on_method' => $data['id_method'], 'on_method_params' => $data['params']]);
            }
        }
    }

    public function updateOffMethod(array $data)
    {
        if (empty($data['id_method'])) {
            View::where('id', $data['id_view'])
                ->update(['off_method' => null, 'off_method_params' => null]);
        } else {
            if (empty($data['params'])) {
                View::where('id', $data['id_view'])
                    ->update(['off_method' => $data['id_method'], 'off_method_params' => null]);
            } else {
                View::where('id', $data['id_view'])
                    ->update(['off_method' => $data['id_method'], 'off_method_params' => $data['params']]);
            }
        }
    }

    public static function getNameById($idView)
    {
        return View::where('id', $idView)->first();
    }
}
