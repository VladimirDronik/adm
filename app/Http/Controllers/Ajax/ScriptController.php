<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\ScriptService;
use Illuminate\Http\Request;

class ScriptController extends Controller
{
    private $service;

    public function __construct(ScriptService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool)$this->service->delete((int)$r->id)]);
    }
}