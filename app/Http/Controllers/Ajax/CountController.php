<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\CountService;
use Illuminate\Http\Request;

class CountController extends Controller
{
    private $service;

    public function __construct(CountService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json(['result' => $this->service->delete((int) $r->id)]);
    }
}
