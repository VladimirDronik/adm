<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\LightstatService;
use Illuminate\Http\Request;

class LightstatController extends Controller
{
    public function __construct(
        private LightstatService $service
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

        return response()->json(['result' => (bool) $this->service->delete((int) $r->id)]);
    }
}
