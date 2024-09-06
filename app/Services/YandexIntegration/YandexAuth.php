<?php

namespace App\Services\YandexIntegration;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Exception\GuzzleException;

class YandexAuth extends BrowserRequests
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Парсим из контента страницы форму с инпутами
     */
    private function parseForm(string $pageCont): array
    {
        $paramArr = [];

        if ($pageCont) {
            $pageCont = str_replace("\r", '', $pageCont);
            $pageCont = str_replace("\n", '', $pageCont);

            preg_match_all("/<FORM(.*?)<\/FORM>/i", $pageCont, $matchForm);
            preg_match_all('/<INPUT(.*?)>/i', $pageCont, $matchInput);

            foreach ($matchInput[1] as $key => $value) {
                preg_match_all('/NAME="(.*?)"/i', $value, $matchName);
                preg_match_all('/VALUE="(.*?)"/i', $value, $matchValue);

                $paramArr[array_key_exists(0, $matchName[1]) ? $matchName[1][0] : ''] = array_key_exists(0, $matchValue[1]) ? $matchValue[1][0] : '';
            }

            unset($paramArr['']);
        }

        return $paramArr;
    }

    /**
     * Прохождение пошаговой авторизации яндекса и запись куки
     */
    public function yaAuth(string $login, string $password, string $cookie, string $referer): array
    {
        if (file_exists($cookie)) {
            unlink($cookie);
        }

        $url = 'https://passport.yandex.ru/auth?';
        $pageCont = $this->browserGetContents($url, $cookie, $referer);
        $paramArr = $this->parseForm($pageCont);

        $paramArr['login'] = $login;
        $paramArr['hidden-password'] = $password;
        $url = 'https://passport.yandex.ru/auth?retpath=https%3A%2F%2Fyandex.ru%2F?';

        $param = '';

        foreach ($paramArr as $key => $value) {
            $param .= '&'.$key.'='.$value;
        }

        $pageCont = $this->browserPostContents($url, $param, $cookie, $referer);
        $paramArr = $this->parseForm($pageCont);

        $paramArr['login'] = $login;
        $paramArr['passwd'] = $password;
        $url = 'https://passport.yandex.ru/auth?retpath=https%3A%2F%2Fyandex.ru%2F?';

        foreach ($paramArr as $key => $value) {
            $param .= '&'.$key.'='.$value;
        }

        $pageCont = $this->browserPostContents($url, $param, $cookie, $referer);

        if (strstr($pageCont, 'https://passport.yandex.ru/auth/finish')) {
            return $this->getXToken($cookie);
        } elseif (strstr($pageCont, 'https://passport.yandex.ru/auth/challenges')) {
            return [
                'code' => 422,
                'message' => 'Авторизация по паролю недоступна. Используйте одноразовый пароль',
            ];
        } else {
            return [
                'code' => 500,
                'message' => 'Ошибка авторизации. Повторите попытку или воспользуйтесь другим способом авторизации',
            ];
        }
    }

    /**
     * Проверка авторизации и попытка получения куки по токену
     */
    public function checkOrGetCookies(string $cookie): array
    {
        $ch = curl_init('https://yandex.ru/quasar?storage=1');
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            Log::error('Error Curl: '.curl_error($ch));
        }

        curl_close($ch);

        if (is_string($response)) {
            $resp = json_decode($response, true);

            if (
                array_key_exists('storage', $resp) &&
                array_key_exists('user', $resp['storage']) &&
                array_key_exists('uid', $resp['storage']['user']) &&
                $resp['storage']['user']['uid']
            ) {
                return ['code' => 200];
            }
        }

        $tokenFile = base_path(config('yandex.token_file'));

        if (file_exists($tokenFile)) {
            $data = json_decode(file_get_contents($tokenFile), true);
            if (array_key_exists('x_token', $data)) {
                return $this->loginToken($data['x_token'], $cookie);
            }
        }

        return ['code' => 401];
    }

    /**
     * Получение куки по токену
     */
    public function loginToken(string $xToken, string $cookieFile): array
    {
        $payload = [
            'type' => 'x-token',
            'retpath' => 'https://www.yandex.ru',
        ];

        $headers = [
            'Ya-Consumer-Authorization' => 'OAuth '.$xToken,
        ];

        $response = '';

        try {
            $response = $this->client->post('https://mobileproxy.passport.yandex.net/1/bundle/auth/x_token/', [
                'form_params' => $payload,
                'headers' => $headers,
            ]);
        } catch (GuzzleException $e) {
            Log::error('Ошибка получения куки по токену:'.$e->getMessage());

            return ['code' => 401];
        }

        $resp = json_decode($response->getBody(), true);

        if (array_key_exists('status', $resp) && $resp['status'] == 'ok') {
            if (array_key_exists('passport_host', $resp) && array_key_exists('track_id', $resp)) {
                $ch = curl_init($resp['passport_host'].'/auth/session/?track_id='.$resp['track_id']);
                curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_exec($ch);

                if (curl_errno($ch)) {
                    Log::error('Error Curl: '.curl_error($ch));
                }

                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                curl_close($ch);

                if ($statusCode == 302) {
                    return ['code' => 200];
                }
            }
        }

        Log::error('Ошибка получения куки по токену');

        return ['code' => 401];
    }

    /**
     * Получение токена по сессии для дальнейшего обновления куки
     *
     * @param  string  $cookie
     */
    public function getXToken(string $cookies): array
    {
        $cookieLines = explode("\n", file_get_contents($cookies));
        $session = '';

        foreach ($cookieLines as $line) {
            $fields = explode("\t", $line);
            if (array_key_exists(5, $fields) && $fields[5] == 'Session_id') {
                $sessionValue = '';
                if (array_key_exists(6, $fields)) {
                    $sessionValue = rtrim($fields[6]);
                }
                $session = 'Session_id='.$sessionValue;
                break;
            }
        }

        $headers = [
            'Ya-Client-Host' => 'passport.yandex.ru',
            'Ya-Client-Cookie' => $session,
        ];

        $payload = [
            'client_id' => 'c0ebe342af7d48fbbbfcf2d2eedb8f9e',
            'client_secret' => 'ad0a908f0aa341a182a37ecd75bc319e',
        ];

        $response = '';

        try {
            $response = $this->client->post('https://mobileproxy.passport.yandex.net/1/bundle/oauth/token_by_sessionid', [
                'headers' => $headers,
                'form_params' => $payload,
            ]);
        } catch (GuzzleException $e) {
            Log::error('Ошибка получения токена по сессии для дальнейшего обновления куки:'.$e->getMessage());

            return [
                'code' => 500,
                'message' => 'Ошибка авторизации. Не удалось получить токен. Повторите попытку или обратитесь к администратору',
            ];
        }

        $resp = json_decode($response->getBody(), true);

        if (array_key_exists('access_token', $resp)) {
            file_put_contents(base_path(config('yandex.token_file')), json_encode(['x_token' => $resp['access_token']]));

            return ['code' => 200];
        } else {
            return [
                'code' => 500,
                'message' => 'Ошибка авторизации. Не удалось получить токен. Повторите попытку или обратитесь к администратору',
            ];
        }
    }

    /**
     * Получение ссылки на qr-код
     */
    public function getQrCode(): array
    {
        $response = '';

        try {
            $response = $this->client->get('https://passport.yandex.ru/am?app_platform=android');
        } catch (GuzzleException $e) {
            Log::error('Ошибка получения яндекс qr-кода:'.$e->getMessage());

            return [
                'code' => 500,
                'message' => 'Ошибка авторизации. Не удалось получить qr-код. Попробуйте другой способ авторизации',
            ];
        }

        preg_match('/"csrf_token" value="([^"]+)"/', $response->getBody()->getContents(), $matches);

        if (! array_key_exists(1, $matches)) {
            return [
                'code' => 500,
                'message' => 'Ошибка авторизации. Не удалось получить qr-код. Попробуйте другой способ авторизации',
            ];
        }

        $csrf_token = $matches[1];

        try {
            $response = $this->client->post('https://passport.yandex.ru/registration-validations/auth/password/submit', [
                'csrf_token' => $csrf_token,
                'retpath' => 'https://passport.yandex.ru/profile',
                'with_code' => 1,
            ]);
        } catch (GuzzleException $e) {
            Log::error('Ошибка получения яндекс qr-кода:'.$e->getMessage());

            return [
                'code' => 500,
                'message' => 'Ошибка авторизации. Не удалось получить qr-код. Попробуйте другой способ авторизации',
            ];
        }

        $resp = json_decode($response->getBody(), true);

        if (array_key_exists('status', $resp) && $resp['status'] == 'ok') {
            if (array_key_exists('csrf_token', $resp) && array_key_exists('track_id', $resp)) {
                Session::put('csrf_token', $resp['csrf_token']);
                Session::put('track_id', $resp['track_id']);

                return [
                    'code' => 200,
                    'qr_url' => 'https://passport.yandex.ru/auth/magic/code/?track_id='.$resp['track_id'],
                ];
            }
        }

        return [
            'code' => 500,
            'message' => 'Ошибка авторизации. Не удалось получить qr-код. Попробуйте другой способ авторизации',
        ];
    }

    /**
     * Авторизация qr-кода
     */
    public function loginQrCode(string $cookie): array
    {
        $payload = [
            'csrf_token' => Session::get('csrf_token'),
            'track_id' => Session::get('track_id'),
        ];

        $ch = curl_init('https://passport.yandex.ru/auth/new/magic/status/');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            Log::error('Error Curl: '.curl_error($ch));
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $resp = json_decode($response, true);

        if ($statusCode == 200) {
            if ($resp && array_key_exists('status', $resp) && $resp['status'] == 'ok') {
                return $this->getXToken($cookie);
            } else {
                return ['code' => 401];
            }
        }

        return [
            'code' => 500,
            'message' => 'Ошибка авторизации. Не удалось авторизовать qr-код. Попробуйте другой способ авторизации',
        ];
    }
}
