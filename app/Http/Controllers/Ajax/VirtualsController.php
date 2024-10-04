<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\VirtualService;

class VirtualsController
{
    public function __construct(
        private VirtualService $service
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
