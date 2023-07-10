<?php

namespace App\Services\YandexIntegration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class YandexAuth
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Получение oauth токена яндекс
     *
     * @param int $code
     * @return bool
     */
    public function getYaOauth(int $code): bool
    {
        try {
            $response = $this->client->post('https://oauth.yandex.ru/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'client_id' => config('yandex.client_id'),
                    'client_secret' => config('yandex.client_secret'),
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (array_key_exists('access_token', $responseData)) {
                file_put_contents(base_path('yandex_token.json'), $response->getBody());
                return 1;
            } else {
                Log::error('Что-то пошло не так. Не удалось получить oauth токен');
                return 0;
            }
        } catch (GuzzleException $e) {
            Log::error('Ошибка получения токена: ' . $e->getMessage());
            return 0;
        }
    }
}
