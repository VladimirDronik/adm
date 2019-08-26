<?php

namespace App\Services;

use App\Models\View;

class ViewService {

    public function concatTitles($data, $prefix)
    {
        return trim($data[$prefix.'_title_top'] ?? '')
            .'<br>'.trim($data[$prefix.'_title_bottom'] ?? '');
    }

    public function store(array $data)
    {
        $view = new View();

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

        $view->save();

        return $view->id;
    }
}