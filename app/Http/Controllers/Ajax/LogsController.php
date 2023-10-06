<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\LogService;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function __construct(
        private LogService $service
    ) {
    }

    public function active(Request $r)
    {
        abort_if(! ajaxHas($r, ['id', 'active']), 400);

        return response()->json(['result' => $this->service->changeActive((int) $r->id, (int) $r->active)]);
    }
}
