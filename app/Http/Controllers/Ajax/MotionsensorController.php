<?php

namespace App\Http\Controllers\Ajax;

use App\Services\MotionsensorService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MotionsensorController extends Controller
{
    private $service;

    public function __construct(MotionsensorService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool)$this->service->delete((int)$r->id)]);
    }
}
