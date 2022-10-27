<?php

namespace App\Repositories;

use App\Models\Menu;

class MenuRepository {


    public function getMenuGroups()
    {
        return Menu::where('parent', '=',0)
            ->orderBy('sort')->get();
    }

    public function getParents($pagination_count = 30)
    {
        return Menu::where('parent', '=',0)
            ->orderBy('sort')->paginate($pagination_count);
    }

    public function getChildren(int $groupId, $pagination_count = 30)
    {
        return Menu::where('parent', '=',$groupId)
            ->orderBy('sort')->paginate($pagination_count);
    }

    public function getAll($pagination_count = 30)
    {
        return Menu::orderBy('sort')->paginate($pagination_count);
    }

    public function getGroup($id)
    {
        return Menu::where('id', $id)->first();
    }
    
    public function getByName(string $name)
    {
    	return Menu::where('name', $name)->first();
    }
}
