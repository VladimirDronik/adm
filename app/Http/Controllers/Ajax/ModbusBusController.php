<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\Modbus\BusService;
use App\Http\Controllers\Controller;

class ModbusBusController extends Controller
{
    public function __construct(
        private BusService $service
    ) {
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json([
            'result' => (bool) $this->service->delete((int) $r->id),
        ]);
    }
}
