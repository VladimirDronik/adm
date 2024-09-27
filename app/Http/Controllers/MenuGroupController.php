<?php

namespace App\Http\Controllers;

use App\Services\MenuService;
use App\Services\ImageService;
use App\Repositories\MenuRepository;

class MenuGroupController
{
    public function __construct(
        private MenuRepository $menuRep,
        private MenuService $service
    ) {
    }

    public function index(int $id)
    {
        $group = $this->menuRep->getGroup($id);

        if (! $group) {
            return redirect()->route('menu.index');
        }

        $menus = $this->menuRep->getChildren($group->id);
        $groups = $this->menuRep->getMenuGroups();
        $images = ImageService::getMainImages();

        return view('menu.group_index', compact(
            'group', 'menus', 'groups', 'images'
        ));
    }
}
