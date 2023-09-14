<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 30.03.21
 * Time: 12:15
 */

namespace App\Http\Controllers;
use App\Repositories\MenuRepository;
use App\Services\MenuService;
use App\Services\ImageService;

class MenuGroupController
{
    public function __construct(
        private MenuRepository $menu_rep,
        private MenuService $service
    )
    {}

    public function index(int $id)
    {
        $group = $this->menu_rep->getGroup($id);

        if (!$group) {
            return redirect()->route('menu.index');
        }

        $menus = $this->menu_rep->getChildren($group->id);
        $groups = $this->menu_rep->getMenuGroups();
        $images = ImageService::getMainImages();

        return view('menu.group_index', compact('group', 'menus',
            'groups',  'images'));
    }
}