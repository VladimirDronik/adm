<?php

namespace App\Http\Controllers\Ajax;

use App\Services\ElementService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ElementController extends Controller
{
    private $service;

    public function __construct(ElementService $service)
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

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => $this->service->delete((int)$r->id)]);
    }

    public function updateImage(Request $r)
    {
        abort_if(!ajaxHas($r, ['id', 'image']), 400);

        $this->service->updateImage((int)$r->id, (string)$r->image);

        return response()->json(['result' => true]);
    }

    public function updateName(Request $r)
    {
        abort_if(!ajaxHas($r, ['id', 'name']), 400);

        $this->service->updateName((int)$r->id, (string)$r->name);

        return response()->json(['success' => true, 'html' => $r->name]);
    }

    public function store(Request $r)
    {
        abort_if(!ajaxHas($r, ['name', 'image', 'style', 'type']), 400);

        return response()->json(['result' => (bool)$this->service->store($r->all())]);
    }


}
