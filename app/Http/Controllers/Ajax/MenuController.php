<?php

namespace App\Http\Controllers\Ajax;

use App\Services\MenuService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    private $service;

    public function __construct(MenuService $service)
    {
        $this->service = $service;
    }

    public function sort(Request $r)
    {
        abort_if(!ajaxHas($r, ['id', 'direction']), 400);

        return response()->json(['result' => $this->service->sort($r->all())]);
    }

    public function active(Request $r)
    {
        abort_if(!ajaxHas($r, ['id','active']), 400);

        return response()->json(['result' => $this->service->changeActive((int)$r->id, (int)$r->active)]);
    }
}
