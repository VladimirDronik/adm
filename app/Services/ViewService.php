<?php

namespace App\Services;

use App\Models\Room;
use App\Models\View;

class ViewService {

    public function prepareView(View $view, array $data)
    {
        $view->title = trim($data['title']);
        $view->type = trim($data['type']);
        $view->scene = $data['scene'] ?? null;
        $view->position_top = (int)$data['position_top'];
        $view->position_left = (int)$data['position_left'];

        $view->room = ((int)$data['room'] === 0) ? null : (int)$data['room'];
        if (is_null($view->room)) {
            $view->room_group = null;
        } else {
            $room = Room::find($view->room);
            if ($room->is_group) {
                $view->room_group = $room->group_room;
            } else {
                $view->room_group = $view->room;
            }
        }

        $view->id_object = $data['id_object'] ?? null;
        $view->id_method = $data['id_method'] ?? null;
        $view->description = trim($data['description']);
        $view->status = 'off';
        $view->active = $data['active'] ?? 0;
        $view->sort = 0;
        $view->icon = pathinfo($data['icon_image'], PATHINFO_FILENAME);
        $view->id_method_params = $data['id_method_params'];
    }

    public function store(array $data)
    {
        $view = new View();
        $this->prepareView($view, $data);
        $view->save();

        return $view->id;
    }

    public function update(View $view, array $data)
    {
        $this->prepareView($view, $data);
        $view->save();

        return $view->id;
    }

    public function delete(int $id)
    {
        return View::destroy($id);
    }

    public function changeActive(int $id, int $active)
    {
        View::where('id', $id)->update(['active' => $active]);

        return true;
    }
}