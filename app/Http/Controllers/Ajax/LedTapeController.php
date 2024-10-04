<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\LedTapeService;
use App\Http\Controllers\Controller;

class LedTapeController extends Controller
{
    private $service;

    public function __construct(LedTapeService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json([
            'result' => $this->service->delete((int) $r->id),
        ]);
    }
}
