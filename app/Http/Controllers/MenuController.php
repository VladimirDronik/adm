<?php

namespace App\Http\Controllers;

use App\Repositories\MenuRepository;;
use App\Services\MenuService;

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
        $menus = $this->menu_rep->getAll();

        return view('menu.index', compact('menus'));
    }
}
