<?php

namespace App\Http\Controllers\Ajax;

use App\Services\MethodService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MethodController extends Controller
{
    private $service;

    public function __construct(MethodService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool)$this->service->delete((int)$r->id)]);
    }

    public function store(Request $r)
    {
        abort_if(!ajaxHas($r, ['object_id', 'id', 'name', 'script_id', 'comment']), 400);

        return response()->json(['result' => true] + $this->service->store($r->all()));
    }
}
