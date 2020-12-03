<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 03.12.20
 * Time: 13:23
 */

namespace App\Http\Controllers\Ajax;


use App\Services\VirtualService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VirtualsController
{
    private $service;

    public function __construct(VirtualService $service)
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

        return response()->json(['result' => $this->service->delete((int)$r->id)]);
    }

}