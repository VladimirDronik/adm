<?php


namespace App\Http\Controllers\Ajax;


use App\Services\YandexStationService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class YandexStationController
{
    private $service;

    public function __construct(YandexStationService $service)
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