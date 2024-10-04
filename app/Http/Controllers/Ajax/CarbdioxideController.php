<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CarbdioxideService;

class CarbdioxideController extends Controller
{
    public function __construct(
        private CarbdioxideService $service
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
