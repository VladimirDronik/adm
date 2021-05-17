<?php

namespace App\Http\Controllers\Ajax;

use App\Services\LockService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LockController extends Controller
{

    private $service;

    public function __construct(LockService $lockService)
    {
        $this->service = $lockService;
    }

    /**
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => $this->service->delete((int)$r->id)]);
    }
}
