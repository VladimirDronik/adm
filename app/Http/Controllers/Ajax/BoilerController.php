<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\BoilerService;
use App\Http\Controllers\Controller;

class BoilerController extends Controller
{
    public function __construct(
        private BoilerService $service
    ) {
    }

    public function boilerAutoDelete(Request $r)
    {
        abort_if(! ajaxHas($r, ['boiler_auto_id']), 400);

        return response()->json([
            'result' => (bool) $this->service->boilerAutoDelete((int) $r->boiler_auto_id),
        ]);
    }
}
