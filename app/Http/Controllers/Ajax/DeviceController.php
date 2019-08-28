<?php

namespace App\Http\Controllers\Ajax;

use App\Services\DeviceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    private $service;

    public function __construct(DeviceService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool)$this->service->delete((int)$r->id)]);
    }

    public function update(Request $r)
    {
        abort_if(!ajaxHas($r, ['id','description','ip_address']), 400);

        return response()->json(['result' => $this->service->update($r->all())]);
    }

    public function updatePort(Request $r)
    {
        abort_if(!ajaxHas($r, ['id','port_id','name','value']), 400);

        return response()->json(['result' => $this->service->updatePort($r->all())]);
    }
}


