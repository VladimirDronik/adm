<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\LockService;
use App\Http\Controllers\Controller;

class LockController extends Controller
{
    public function __construct(
        private LockService $service
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
