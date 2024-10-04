<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\EngineeringService;

class EngineeringController extends Controller
{
    public function __construct(
        private EngineeringService $service
    ) {
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json([
            'result' => $this->service->delete((int) $r->id, (bool) $r->del_checkbox),
        ]);
    }
}
