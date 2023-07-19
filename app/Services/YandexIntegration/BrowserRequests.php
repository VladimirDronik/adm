<?php

namespace App\Services\YandexIntegration;

use Illuminate\Support\Facades\Log;

class BrowserRequests
{
    /**
     * Генерация get запроса с симуляцией браузера
     *
     * @param string $url
     * @param string $cookie
     * @param string $referer
     * @param string $header
     * @return string
     */
    public function browserGetContents(string $url, string $cookie, string $referer = 'https://google.by',  string $header = ''): string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $html = curl_exec($ch);
        $info_arr = curl_getinfo($ch);

        if ($info_arr['redirect_url']) {
            $html = $info_arr['redirect_url'];
        }

        if (curl_errno($ch)) {
            Log::error("Error Curl: " . curl_error($ch));
        }

        curl_close($ch);

        return $html;
    }

    /**
     * Генерация post запроса с симуляцией браузера
     *
     * @param string $url
     * @param string $cookie
     * @param string $referer
     * @param string $header
     * @return string
     */
    public function browserPostContents(string $url, string $param, string $cookie, string $referer = 'https://google.by', string $header = ''): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $html = curl_exec($ch);
        $info_arr = curl_getinfo($ch);

        if ($info_arr['redirect_url']) {
            $html = $info_arr['redirect_url'];
        }

        if (curl_errno($ch)) {
            Log::error('Error Curl: ' . curl_error($ch));
        }

        curl_close($ch);

        return $html;
    }
}
