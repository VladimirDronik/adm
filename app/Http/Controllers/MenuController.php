<?php

namespace App\Http\Controllers;

use App\Repositories\MenuRepository;;
use App\Services\MenuService;
use App\Services\ImageService;
use App\Models\Menu;
use App\Http\Requests\Menu\UpdateRequest;

class MenuController extends Controller
{
    private $menu_rep;
    private $service;

    public function __construct(MenuRepository $menu_rep, MenuService $service)
    {
        $this->menu_rep = $menu_rep;
        $this->service = $service;
    }

    public function index()
    {
        $menus = $this->menu_rep->getParents();
        $groups = $this->menu_rep->getMenuGroups();
        $images = ImageService::getMainImages();

        return view('menu.index', compact('menus', 'groups', 'images'));
    }

    public function edit(Menu $menu)
    {
        /*
        if ($menu->is_group) {
            return redirect()->route('rooms.index');
        }
*/

       $groups = $this->menu_rep->getMenuGroups()->pluck('name', 'id')->toArray();

        return view('menu.edit_menu', compact('menu', 'groups'));
    }


    public function update(UpdateRequest $r, Menu $menu)
    {
        try {
            if ($this->service->update($menu, $r->except('_token'))) {
                return redirect()->route('menu.edit',[$menu->id])->with('success','Настройки успешно изменены');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении настроек меню'.$menu->id.' '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении настроек меню');
    }

}
