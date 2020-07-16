<?php

namespace App\Http\Controllers\Ajax;

use App\Services\ConfigMegaService;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    private $service;
    private $megaService;

    public function __construct(DeviceService $service, ConfigMegaService $megaService)
    {
        $this->service = $service;
        $this->megaService = $megaService;
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool)$this->service->delete((int)$r->id)]);
    }

    /**
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $r)
    {
        abort_if(!ajaxHas($r, ['id','description','ip_address']), 400);

        list($result, $message) = $this->service->update($r->all());

        return response()->json(compact('result','message', 'count_all', 'count_result'));
    }

    public function objectsPorts(Request $r)
    {
        abort_if(!ajaxHas($r, ['device_id']), 400);

        $ports = $this->service->getPortsWithObjectsByDeviceId((int)$r->device_id,
            $r->has('status') ? $r->status : '');

        return response()->json(['result' => true, 'ports' => $ports]);
    }

    // todo
    public function updatePort(Request $r)
    {
        abort_if(!ajaxHas($r, ['id','port_id','name','value']), 400);

        return response()->json(['result' => $this->service->updatePort($r->all())]);
    }

    public function ports(Request $r)
    {
        abort_if(!ajaxHas($r, ['device_id']), 400);

        $ports = $this->service->getPortsByDeviceId((int)$r->device_id);

        return response()->json(['result' => true, 'ports' => $ports]);
    }

    public function checkServer(Request $r)
    {
        return response()->json(['result' => true]);
    }


}


