<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\MethodService;
use App\Http\Controllers\Controller;

class MethodController extends Controller
{
    public function __construct(
        private MethodService $service
    ) {
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json([
            'result' => (bool) $this->service->delete((int) $r->id),
        ]);
    }

    public function store(Request $r)
    {
        abort_if(! ajaxHas($r, ['data']), 400);

        return response()->json([
            'result' => true,
            'data' => $this->service->store($r->data),
        ]);
    }
}
