<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Services\MenuService;
use App\Services\ImageService;
use Illuminate\Support\Facades\Log;
use App\Repositories\MenuRepository;
use App\Http\Requests\Menu\UpdateRequest;

class MenuController extends Controller
{
    public function __construct(
        private MenuRepository $menuRep,
        private MenuService $service
    ) {
    }

    public function index()
    {
        $menus = $this->menuRep->getParents();
        $groups = $this->menuRep->getMenuGroups();
        $images = ImageService::getMainImages();

        return view('menu.index', compact(
            'menus', 'groups', 'images'
        ));
    }

    public function edit(Menu $menu)
    {
        $groups = $this->menuRep->getMenuGroups()
            ->pluck('name', 'id')->toArray();

        return view('menu.edit_menu', compact('menu', 'groups'));
    }

    public function update(UpdateRequest $r, Menu $menu)
    {
        try {
            if ($this->service->update($menu, $r->except('_token'))) {
                return redirect()
                    ->route('menu.edit', [$menu->id])
                    ->with('success', 'Настройки успешно изменены');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении настроек меню'.$menu->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении настроек меню');
    }
}
