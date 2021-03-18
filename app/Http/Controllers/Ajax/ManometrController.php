<?php

namespace App\Http\Controllers\Ajax;

use App\Services\ManometrService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManometrController extends Controller
{
    private $service;

    public function __construct(ManometrService $service)
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
