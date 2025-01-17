<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\RegulatorService;
use App\Http\Controllers\Controller;

class RegulatorController extends Controller
{
    public function __construct(
        private RegulatorService $service
    ) {
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json([
            'result' => $this->service->delete((int) $r->id),
        ]);
    }
}
