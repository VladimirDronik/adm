<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']) || ! \Gate::allows('settings.delete'), 400);

        return response()->json(['result' => (bool) $this->service->delete((int) $r->id)]);
    }
}
