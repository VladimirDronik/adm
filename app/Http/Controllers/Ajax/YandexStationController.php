<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\YandexStationRepository;
use App\Services\YandexStationService;
use Illuminate\Http\Request;

class YandexStationController
{
    public function __construct(
        private YandexStationService $service,
        private YandexStationRepository $repository
    ) {
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json(['result' => $this->service->delete((int) $r->id)]);
    }

    public function load()
    {
        $stations = $this->repository->getStationsToArray();

        foreach ($stations as $station) {
            $stationsArray[] = ['id' => $station['id'], 'name' => $station['name'], 'volume' => $station['volume']];
        }

        return response()->json(['stations' => $stationsArray]);
    }
}
