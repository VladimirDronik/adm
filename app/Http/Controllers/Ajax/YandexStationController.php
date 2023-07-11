<?php


namespace App\Http\Controllers\Ajax;


use App\Repositories\YandexStationRepository;
use App\Services\YandexStationService;
use Illuminate\Http\Request;
use App\Services\YandexIntegration\YandexQuasar;
use Illuminate\Support\Facades\Log;

class YandexStationController
{
    private $service;
    private $repository;
    private $yandexQuasar;

    public function __construct(
        YandexStationService $service,
        YandexStationRepository $repository,
        YandexQuasar $yandexQuasar
    )
    {
        $this->service = $service;
        $this->repository = $repository;
        $this->yandexQuasar = $yandexQuasar;
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

    /**
     * Авторизация в яндексе и получение станций
     */
    public function auth(Request $r)
    {
        $validated = $r->validate([
            'code' => 'required|integer',
        ]);

        $yaAuth = $this->yandexQuasar->getYaOauth($validated['code']);

        return response()->json(['result' => $yaAuth]);
    }

    /**
     * Синхронизировать станции
     */
    public function syncStations()
    {
        $response = $this->yandexQuasar->getStations();

        if ($response['code'] == 200 && array_key_exists('devices', $response)) {
            return response()->json(['code' => $this->service->store($response['devices']) ? 200 : 500]);
        } elseif ($response['code'] == 401) {
            return response()->json($response);
        } else {
            Log::error('Ошибка синхронизации станций: ' . $response['message']);
            return response()->json($response);
        }
    }
}