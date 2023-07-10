<?php

namespace App\Services\YandexIntegration;

use GuzzleHttp\Exception\GuzzleException;

class YandexQuasar extends YandexAuth
{
    /**
     * Получение списка станций
     *
     * @return null|array
     */
    public function getStations(): array
    {
        $oauthToken = '';

        if (file_exists(base_path('yandex_token.json'))) {
            $data = json_decode(file_get_contents(base_path('yandex_token.json')), true);
            if (array_key_exists('access_token', $data)) {
                $oauthToken = $data['access_token'];
            } else {
                return ['code' => 401];
            }
        } else {
            return ['code' => 401];
        }

        try {
            $response = $this->client->get('https://api.iot.yandex.net/v1.0/user/info', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Bearer ' . $oauthToken,
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $data['code'] = 200;

            return $data;
        } catch (GuzzleException $e) {
            return [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }
}
