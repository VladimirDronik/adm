<?php

namespace App\Services\YandexIntegration;

use Illuminate\Support\Facades\Log;

class YandexQuasar extends BrowserRequests
{
    private $yandexAuth;
    private $yaLogin;
    private $yaPassword;

    public function __construct(string $yaLogin, string $yaPassword)
    {
        $this->yaLogin = $yaLogin;
        $this->yaPassword = $yaPassword;
        $this->yandexAuth = new YandexAuth();
    }

    /**
     * Получение списка станций
     *
     * @return null|array
     */
    public function getStations(): string
    {
        $cookie = "cookie_yandex_ru";
        $referer = "https://passport.yandex.ru/auth/";

        for ($i = 0; $i < 5; $i++) {
            $yaAuth = $this->yandexAuth->yaAuth($this->yaLogin, $this->yaPassword, $cookie, $referer);
        }

        if (!$yaAuth) {
            if (file_exists(base_path($cookie . '.txt'))) {
                unlink(base_path($cookie . '.txt'));
            }
            Log::error('Яндекс Станция: Что-то пошло не так! Не удалось авторизироваться в Яндексе.');
        }

        $url = 'https://iot.quasar.yandex.ru/m/user/devices';
        $response = $this->browserGetContents($url, $cookie, $referer);

        return $response;
    }
}
