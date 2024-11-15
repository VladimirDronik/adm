<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ModbusRepository;
use App\Services\Modbus\SlaverService;

class ModbusSlaverController extends Controller
{
    public function __construct(
        private SlaverService $service,
        private ModbusRepository $modbusRep
    ) {
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json([
            'result' => (bool) $this->service->delete((int) $r->id),
        ]);
    }

    public function getRegisters(Request $r)
    {
        abort_if(! ajaxHas($r, ['slaver_id']), 400);

        $registers = $this->modbusRep
            ->getRegistersBySlaverToArray((int) $r->slaver_id);

        return response()->json($registers);
    }

    public function networkAssembly(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        $response = $this->service->networkAssembly((int) $r->id);

        return response()->json([
            'result' => $response['code'] === 0,
            'message' => array_key_exists(0, $response['output']) ? $response['output'][0] : null,
        ]);
    }

    public function networkExpansion(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        $response = $this->service->networkExpansion((int) $r->id);

        return response()->json([
            'result' => $response['code'] === 0,
            'message' => array_key_exists(0, $response['output']) ? $response['output'][0] : null,
        ]);
    }

    public function switchStatus(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_object']), 400);

        $response = $this->service->switchDaliStatus((int) $r->id_object);

        return response()->json([
            'result' => $response['code'] === 0,
            'message' => array_key_exists(0, $response['output']) ? $response['output'][0] : null,
        ]);
    }

    public function setBrightness(Request $r)
    {
        abort_if(! ajaxHas($r, ['id', 'brightness']), 400);

        $response = $this->service->setDaliBrightness((int) $r->id, $r->brightness);

        return response()->json([
            'result' => $response['code'] === 0,
            'message' => array_key_exists(0, $response['output']) ? $response['output'][0] : null,
        ]);
    }

    public function setCct(Request $r)
    {
        abort_if(! ajaxHas($r, ['id', 'cct']), 400);

        $response = $this->service->setDaliCct((int) $r->id, $r->cct);

        return response()->json([
            'result' => $response['code'] === 0,
            'message' => array_key_exists(0, $response['output']) ? $response['output'][0] : null,
        ]);
    }

    public function addDaliDeviceToGroup(Request $r)
    {
        abort_if(! ajaxHas($r, ['dali_device_id_object', 'group_id_object', 'group_address']), 400);

        $response = $this->service->addDaliDeviceToGroup(
            (int) $r->dali_device_id_object,
            (int) $r->group_id_object,
            (int) $r->group_address
        );

        return response()->json([
            'result' => $response['code'] === 0,
            'message' => array_key_exists(0, $response['output']) ? $response['output'][0] : null,
        ]);
    }

    public function removeDaliDeviceFromGroup(Request $r)
    {
        abort_if(! ajaxHas($r, ['dali_device_id_object', 'group_id_object', 'group_address']), 400);

        $response = $this->service->removeDaliDeviceFromGroup(
            (int) $r->dali_device_id_object,
            (int) $r->group_id_object,
            (int) $r->group_address
        );

        return response()->json([
            'result' => $response['code'] === 0,
            'message' => array_key_exists(0, $response['output']) ? $response['output'][0] : null,
        ]);
    }

    public function createDaliDeviceGroup(Request $r)
    {
        abort_if(! ajaxHas($r, ['slaver_id', 'group_address']), 400);

        $this->service->createDaliDeviceGroup(
            (int) $r->slaver_id,
            (int) $r->group_address
        );

        return response()->json([
            'result' => true,
        ]);
    }

    public function removeDaliDeviceGroup(Request $r)
    {
        abort_if(! ajaxHas($r, ['group_id']), 400);

        $result = $this->service->removeDaliDeviceGroup((int) $r->group_id);

        return response()->json([
            'result' => $result,
        ]);
    }
}
