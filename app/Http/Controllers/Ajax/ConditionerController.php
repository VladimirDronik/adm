<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ConditionerService;

class ConditionerController extends Controller
{
    public function __construct(
        private ConditionerService $service
    ) {
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json(['result' => $this->service->delete((int) $r->id)]);
    }

    public function setStatus(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_object', 'status']), 400);

        $data = $this->service->setStatus((int)$r->id_object, (string)$r->status);

        return response()->json([
            'result' => $data['code'] === 0,
            'response' => array_key_exists(0, $data['output']) ? $data['output'][0] : null,
        ]);
    }

    public function setTemp(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_object', 'temp']), 400);

        $data = $this->service->setTemp((int)$r->id_object, (int)$r->temp);

        return response()->json([
            'result' => $data['code'] === 0,
            'response' => array_key_exists(0, $data['output']) ? $data['output'][0] : null,
        ]);
    }

    public function setMode(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_object', 'mode']), 400);

        $data = $this->service->setMode((int)$r->id_object, (string)$r->mode);

        return response()->json([
            'result' => $data['code'] === 0,
            'response' => array_key_exists(0, $data['output']) ? $data['output'][0] : null,
        ]);
    }

    public function setFan(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_object', 'fan']), 400);

        $data = $this->service->setFan((int)$r->id_object, (string)$r->fan);

        return response()->json([
            'result' => $data['code'] === 0,
            'response' => array_key_exists(0, $data['output']) ? $data['output'][0] : null,
        ]);
    }

    public function setVdir(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_object', 'vdir']), 400);

        $data = $this->service->setVdir((int)$r->id_object, (string)$r->vdir);

        return response()->json([
            'result' => $data['code'] === 0,
            'response' => array_key_exists(0, $data['output']) ? $data['output'][0] : null,
        ]);
    }

    public function setHdir(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_object', 'hdir']), 400);

        $data = $this->service->setHdir((int)$r->id_object, (string)$r->hdir);

        return response()->json([
            'result' => $data['code'] === 0,
            'response' => array_key_exists(0, $data['output']) ? $data['output'][0] : null,
        ]);
    }
}
