<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\UsensorService;
use App\Http\Controllers\Controller;

class UsensorController extends Controller
{
    public function __construct(
        private UsensorService $service
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
