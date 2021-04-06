<?php

namespace App\Http\Controllers\Ajax;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\PageService;

class PageController extends Controller
{
    private $service;

    public function __construct(PageService $service)
    {
        $this->service = $service;
    }

    public function updateName(Request $r)
    {
        abort_if(!ajaxHas($r, ['id', 'name']), 400);

        $this->service->updateName((int)$r->id, (string)$r->name);

        return response()->json(['success' => true, 'html' => $r->name]);
    }



    public function updateLink(Request $r)
    {
        abort_if(!ajaxHas($r, ['id', 'link']), 400);

        $this->service->updateLink((int)$r->id, (string)$r->link);

        return response()->json(['success' => true, 'html' => $r->link]);
    }


}
