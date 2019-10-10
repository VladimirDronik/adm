<?php

namespace App\Repositories;

use App\Models\Menu;

class MenuRepository {

    public function getAll($pagination_count = 30)
    {
        return Menu::orderBy('sort')->paginate($pagination_count);
    }
}