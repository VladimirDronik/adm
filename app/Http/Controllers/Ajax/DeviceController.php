<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Device;
use App\Repositories\DeviceRepository;
use App\Services\ConfigMegaService;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    private $service;
    private $megaService;
    private $deviceRepository;

    public function __construct(DeviceService $service, ConfigMegaService $megaService,
                                DeviceRepository $deviceRepository)
    {
        $this->service = $service;
        $this->megaService = $megaService;
        $this->deviceRepository = $deviceRepository;
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool)$this->service->delete((int)$r->id)]);
    }

    public function extensionModuleDelete(Request $r)
    {
        abort_if(!ajaxHas($r, ['extension_module_id']), 400);

        return response()->json(['result' => (bool)$this->service->extensionModuleDelete((int)$r->extension_module_id)]);
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

        return response()->json(compact('result','message'));
    }

    public function objectsPorts(Request $r)
    {
        abort_if(!ajaxHas($r, ['device_id']), 400);

        $typeDevice = '';

        if($r->device_id)
        $typeDevice = DeviceRepository::getDevByIdDevice((int)$r->device_id)['type'];

        $ports = $this->service->getPortsWithObjectsByDeviceId((int)$r->device_id, $r->has('status') ? $r->status : '');

        return response()->json([
            'result' => true, 'ports' => $ports,
            'type_device' => $typeDevice,
            'hiteProDevices' => [],
        ]);
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

    public function typeController(Request $r) {

        if($r->id_device)
            $typeDevice = DeviceRepository::getDevByIdDevice((int)$r->id_device)['type'];

        return response()->json(['result' => true, 'type' =>  $typeDevice]);
    }


    //Получаем контроллеры для вывода в список контроллеров
    public function get(Request $r) {

        if($r->types) {
            $devices = $this->deviceRepository->getAllByTypesToArray($r->types, false);
        }

        return response()->json(['result' => true, 'devices' =>  $devices]);
    }


}


