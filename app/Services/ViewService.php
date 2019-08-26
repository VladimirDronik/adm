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

        $view->fill(collect($data)->only('type','scene','room','position_top','position_left')->toArray());

        $view->name = '?';
        $view->status = '?';
        $view->active = 1;
        $view->date = '?';
        $view->sort = 1;
        $view->items = '?';
        $view->on_image = '?';
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

    public function delete($id)
    {
        return View::destroy($id);
    }
}