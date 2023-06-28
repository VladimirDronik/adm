<?php

namespace App\Http\Controllers\Ajax;

use App\Services\BoilerService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BoilerController extends Controller
{
    private $service;

    public function __construct(BoilerService $boilerService)
    {
        $this->service = $boilerService;
    }

    public function boilerAutoDelete(Request $r)
    {
        abort_if(!ajaxHas($r, ['boiler_auto_id']), 400);

        return response()->json(['result' => (bool)$this->service->boilerAutoDelete((int)$r->boiler_auto_id)]);
    }
}
