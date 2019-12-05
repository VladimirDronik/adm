<?php

namespace App\Http\Controllers\Ajax;

use App\Services\SettingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    private $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool)$this->service->delete((int)$r->id)]);
    }
}
