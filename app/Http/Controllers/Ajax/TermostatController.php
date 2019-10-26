<?php

namespace App\Http\Controllers\Ajax;

use App\Services\TermostatService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TermostatController extends Controller
{
    private $service;

    public function __construct(TermostatService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool)$this->service->delete((int)$r->id)]);
    }
}
