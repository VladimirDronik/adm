<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\Modbus\RegisterService;
use Illuminate\Http\Request;

class ModbusRegisterController extends Controller
{
    public function __construct(
        private RegisterService $service
    ) {
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool) $this->service->delete((int) $r->id)]);
    }

    public function read(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        $data = $this->service->read((int)$r->id);

        return response()->json([
            'result' => $data['code'] === 0,
            'response' => array_key_exists(0, $data['output']) ? $data['output'][0] : null,
        ]);
    }
}
