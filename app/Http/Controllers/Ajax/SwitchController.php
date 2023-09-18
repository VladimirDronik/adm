<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\SwitchService;
use Illuminate\Http\Request;

class SwitchController extends Controller
{
    public function __construct(
        private SwitchService $service
    ) {
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json(['result' => $this->service->delete((int) $r->id)]);
    }
}
