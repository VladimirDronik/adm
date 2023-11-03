<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\RecorderService;
use Illuminate\Http\Request;

class RecorderController extends Controller
{
    public function __construct(
        private RecorderService $service
    ) {
    }

    public function sort(Request $r)
    {
        abort_if(! ajaxHas($r, ['id', 'direction']), 400);

        return response()->json(['result' => $this->service->sort($r->all())]);
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool)$this->service->delete((int) $r->id)]);
    }
}
