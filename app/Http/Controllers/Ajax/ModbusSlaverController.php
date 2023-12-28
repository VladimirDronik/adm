<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\ModbusRepository;
use App\Services\Modbus\SlaverService;
use Illuminate\Http\Request;

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

        return response()->json(['result' => (bool) $this->service->delete((int) $r->id)]);
    }

    public function getRegisters(Request $r)
    {
        abort_if(! ajaxHas($r, ['slaver_id']), 400);

        $registers = $this->modbusRep->getRegistersBySlaverToArray((int) $r->slaver_id);

        return response()->json($registers);
    }
}
