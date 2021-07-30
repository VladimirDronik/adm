<?php


namespace App\Http\Controllers\Ajax;


use App\Repositories\YandexStationRepository;
use App\Services\YandexStationService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class YandexStationController
{
    private $service;
    private $repository;

    public function __construct(YandexStationService $service, YandexStationRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
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

    public function load()
    {
        $stations = $this->repository->getStationsToArray();

        foreach ($stations AS $station) {
            $stationsArray[] = ['id' => $station['id'], 'name' => $station['name'], 'volume' => $station['volume']];
        }

        return response()->json(['stations' => $stationsArray]);
    }
}