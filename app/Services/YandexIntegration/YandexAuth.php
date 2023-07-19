<?php

namespace App\Services\YandexIntegration;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class YandexAuth extends BrowserRequests
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Парсим из контента страницы форму с инпутами
     *
     * @param string $pageCont
     * @return array
     */
    private function parseForm(string $pageCont): array
    {
        $paramArr = [];

        if ($pageCont) {
            $pageCont = str_replace("\r" , "", $pageCont);
            $pageCont = str_replace("\n" , "", $pageCont);

            preg_match_all("/<FORM(.*?)<\/FORM>/i", $pageCont, $matchForm);
            preg_match_all("/<INPUT(.*?)>/i", $pageCont, $matchInput);

            foreach($matchInput[1] as $key => $value) {
                preg_match_all("/NAME=\"(.*?)\"/i", $value, $matchName);
                preg_match_all("/VALUE=\"(.*?)\"/i", $value, $matchValue);

                $paramArr[$matchName[1][0]] = array_key_exists(0, $matchValue[1]) ? $matchValue[1][0] : '';
            }

            unset($paramArr['']);
        }

        return $paramArr;
    }

    /**
     * Прохождение пошаговой авторизации яндекса и запись куки
     *
     * @param string $login
     * @param string $password
     * @param string $cookie
     * @param string $referer
     * @return array
     */
    public function yaAuth(string $login, string $password, string $cookie, string $referer): array
    {
        if (file_exists($cookie)) {
            unlink($cookie);
        }

        $url = "https://passport.yandex.ru/auth?";
        $pageCont = $this->browserGetContents($url, $cookie, $referer);
        $paramArr = $this->parseForm($pageCont);

        $paramArr['login'] = $login;
        $paramArr['hidden-password'] = $password;
        $url = "https://passport.yandex.ru/auth?retpath=https%3A%2F%2Fyandex.ru%2F?";

        $param = '';

        foreach($paramArr as $key => $value) {
            $param .= "&" . $key . "=" . $value;
        }

        $pageCont = $this->browserPostContents($url, $param, $cookie, $referer);
        $paramArr = $this->parseForm($pageCont);

        $paramArr['login'] = $login;
        $paramArr['passwd'] = $password;
        $url = "https://passport.yandex.ru/auth?retpath=https%3A%2F%2Fyandex.ru%2F?";

        foreach($paramArr as $key => $value) {
            $param .= "&" . $key . "=" . $value;
        }

        $pageCont = $this->browserPostContents($url, $param, $cookie, $referer);

        if (strstr($pageCont, "https://passport.yandex.ru/auth/finish")) {
            return $this->getXToken($cookie);
        } elseif (strstr($pageCont, "https://passport.yandex.ru/auth/challenges")) {
            return [
                'code' => 422,
                'message' => 'Авторизация по паролю недоступна. Используйте одноразовый пароль'
            ];
        } else {
            return [
                'code' => 500,
                'message' => 'Ошибка авторизации. Повторите попытку или обратитесь к администратору'
            ];
        }
    }

    /**
     * Проверка авторизации и попытка получения куки по токену
     *
     * @param string $cookie
     * @return array
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
            Log::error("Error Curl: " . curl_error($ch));
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
     *
     * @param string $xToken
     * @param string $cookieFile
     * @return array
     */
    public function loginToken(string $xToken, string $cookieFile): array
    {
        $payload = [
            'type' => 'x-token',
            'retpath' => 'https://www.yandex.ru',
        ];

        $headers = [
            'Ya-Consumer-Authorization' => 'OAuth ' . $xToken,
        ];

        $response = $this->client->post('https://mobileproxy.passport.yandex.net/1/bundle/auth/x_token/', [
            'form_params' => $payload,
            'headers' => $headers,
        ]);

        $resp = json_decode($response->getBody(), true);

        if (array_key_exists('status', $resp) && $resp['status'] == 'ok') {
            if (array_key_exists('passport_host', $resp) && array_key_exists('track_id', $resp)) {
                $ch = curl_init($resp['passport_host'] . '/auth/session/?track_id=' . $resp['track_id']);
                curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_exec($ch);

                if (curl_errno($ch)) {
                    Log::error("Error Curl: " . curl_error($ch));
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
     * @param string $cookie
     * @return array
     */
    public function getXToken(string $cookies)
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
                $session = 'Session_id=' . $sessionValue;
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

        $response = $this->client->post('https://mobileproxy.passport.yandex.net/1/bundle/oauth/token_by_sessionid', [
            'headers' => $headers,
            'form_params' => $payload,
        ]);

        $resp = json_decode($response->getBody(), true);

        if (array_key_exists('access_token', $resp)) {
            file_put_contents(base_path(config('yandex.token_file')), json_encode(['x_token' => $resp['access_token']]));
            return ['code' => 200];
        } else {
            return [
                'code' => 500,
                'message' => 'Ошибка авторизации. Не удалось получить токен. Повторите попытку или обратитесь к администратору'
            ];
        }
    }
}
