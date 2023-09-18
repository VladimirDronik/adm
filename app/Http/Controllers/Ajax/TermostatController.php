<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\TermostatService;
use Illuminate\Http\Request;

class TermostatController extends Controller
{
    public function __construct(
        private TermostatService $service
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
