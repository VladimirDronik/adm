<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\HygrostatService;
use Illuminate\Http\Request;

class HygrostatController extends Controller
{
    public function __construct(
        private HygrostatService $service
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
