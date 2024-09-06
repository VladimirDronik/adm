<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\YandexStationRepository;
use App\Services\YandexIntegration\YandexAuth;
use App\Services\YandexIntegration\YandexTTS;
use App\Services\YandexStationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class YandexStationController
{
    public function __construct(
        private YandexStationService $service,
        private YandexStationRepository $repository,
        private YandexAuth $yandexAuth
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

    /**
     * Авторизация в яндексе
     */
    public function auth(Request $r)
    {
        $validated = $r->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $cookie = base_path(config('yandex.cookie_file'));
        $token = base_path(config('yandex.token_file'));

        for ($i = 0; $i < 5; $i++) {
            $yaAuth = $this->yandexAuth
                ->yaAuth($validated['login'], $validated['password'], $cookie, 'https://passport.yandex.ru/auth/');

            if ($yaAuth['code'] == 200) {
                break;
            }
        }

        if ($yaAuth['code'] !== 200) {
            if (file_exists($cookie)) {
                unlink($cookie);
            }

            if (file_exists($token)) {
                unlink($token);
            }
            Log::error('Яндекс Станция: Что-то пошло не так! Не удалось авторизироваться в Яндексе.');
        }

        return response()->json($yaAuth);
    }

    /**
     * Синхронизировать станции
     */
    public function syncStations()
    {
        $response = $this->yandexAuth->checkOrGetCookies(base_path(config('yandex.cookie_file')));

        if ($response['code'] == 200) {
            $tts = new YandexTTS();
            $ttsResponse = $tts->init();

            return response()->json(['code' => $ttsResponse ? 200 : 500]);
        }

        return response()->json($response);
    }

    /**
     * Получить ссылку на qr
     */
    public function getQr()
    {
        return response()->json($this->yandexAuth->getQrCode());
    }

    /**
     * Проверить авторизацию по qr коду
     */
    public function loginQr()
    {
        $cookie = base_path(config('yandex.cookie_file'));
        $token = base_path(config('yandex.token_file'));

        $loginQrCode = $this->yandexAuth->loginQrCode($cookie);

        if ($loginQrCode['code'] !== 200) {
            if (file_exists($cookie)) {
                unlink($cookie);
            }

            if (file_exists($token)) {
                unlink($token);
            }
            Log::error('Яндекс Станция: Что-то пошло не так! Не удалось авторизироваться в Яндексе.');
        }

        return response()->json($loginQrCode);
    }
}
