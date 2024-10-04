<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\LightstatService;
use App\Http\Controllers\Controller;

class LightstatController extends Controller
{
    public function __construct(
        private LightstatService $service
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
