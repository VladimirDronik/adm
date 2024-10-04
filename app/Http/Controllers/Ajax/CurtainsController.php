<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\CurtainService;
use App\Http\Controllers\Controller;

class CurtainsController extends Controller
{
    public function __construct(
        private CurtainService $service
    ) {
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json([
            'result' => $this->service->delete((int) $r->id),
        ]);
    }

    public function setStatus(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_object', 'status']), 400);

        $data = $this->service->setStatus((int) $r->id_object, (string) $r->status);

        return response()->json([
            'result' => $data['code'] === 0,
            'response' => array_key_exists(0, $data['output']) ? $data['output'][0] : null,
        ]);
    }

    public function setPercent(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_object', 'percent']), 400);

        $data = $this->service->setPercent((int) $r->id_object, (int) $r->percent);

        return response()->json([
            'result' => $data['code'] === 0,
            'response' => array_key_exists(0, $data['output']) ? $data['output'][0] : null,
        ]);
    }

    public function stop(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_object']), 400);

        $data = $this->service->stop((int) $r->id_object);

        return response()->json([
            'result' => $data['code'] === 0,
            'response' => array_key_exists(0, $data['output']) ? $data['output'][0] : null,
        ]);
    }
}
