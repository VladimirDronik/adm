<?php

namespace App\Http\Controllers\Ajax;

use App\Services\UsensorService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsensorController extends Controller
{
    private $service;

    public function __construct(UsensorService $service)
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