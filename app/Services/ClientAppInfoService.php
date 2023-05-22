<?php

namespace App\Services;

use App\Models\ClientInfo;

class ClientAppInfoService {

    /**
     * Обновление данных о клиенте
     *
     * @param ClientInfo $clientInfo
     * @param array $data
     * @return ClientInfo
     */
    public function update(ClientInfo $clientInfo, array $data): ClientInfo
    {
        $clientInfo->update([
            'name' => $data['name'],
            'address' => $data['address'],
        ]);

        return $clientInfo;
    }

    /**
     * Получить версию админ-панели
     *
     * @return string
     */
    public function getAdminVersion(): string
    {
        $data = collect(file(base_path('readme.md')));

        $version = $data->filter(function ($item) {
            return strpos($item, 'ver') !== false;
        });

        $version = $version->toArray();

        if (array_key_exists(0, array_values($version))) {
            return str_replace([' ver ', "\r\n"], '', array_values($version)[0]);
        } else {
            return 'Неизвестно';
        }
    }

    /**
     * Получить версию ядра
     *
     * @return string
     */
    public function getCoreVersion(): string
    {
        $data = collect(file(env('SERVER_FOLDER').'README.MD'));

        $version = $data->filter(function ($item) {
            return strpos($item, 'ver') !== false;
        });

        $version = $version->toArray();

        if (array_key_exists(0, array_values($version))) {
            return str_replace(['current ver ', "\r\n"], '', array_values($version)[0]);
        } else {
            return 'Неизвестно';
        }
    }
}
