<?php

namespace App\Services;

use App\Models\View;

class ViewService {

    public function concatTitles(array $data, string $prefix)
    {
        return trim($data[$prefix.'_title_top'] ?? '')
            .'<br>'.trim($data[$prefix.'_title_bottom'] ?? '');
    }

    public function prepareView(View $view, array $data)
    {
        $view->on_title = $this->concatTitles($data, 'on');
        $view->off_title = $this->concatTitles($data, 'off');

        $view->type_name = trim($data['type_name']);
        $view->scene = $data['scene'] ?? null;
        $view->position_top = (int)$data['position_top'];
        $view->position_left = (int)$data['position_left'];
        $view->room = (int)$data['room'];
        $view->name = trim($data['name']);
        $view->id_object = $data['id_object'] ?? null;
        $view->id_method = $data['id_method'] ?? null;
        $view->description = trim($data['description']);
        $view->status = 'off';
        $view->active = $data['active'] ?? 0;
        $view->sort = 0;
        $view->value = null;
        $view->on_image = basename($data['on_image']);
        $view->off_image = basename($data['off_image']);
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