<?php

namespace App\Services\YandexIntegration;

class YandexAuth extends BrowserRequests
{
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
     * @return bool
     */
    public function yaAuth(string $login, string $password, string $cookie, string $referer): bool
    {
        $param = '';

        $url = "https://frontend.vh.yandex.ru/csrf_token";
        $pageCont = $this->browserGetContents($url, $cookie, $referer);

        if (strlen($pageCont) == 33) {
            return 1;
        }

        if (file_exists(base_path($cookie . '.txt'))) {
            unlink(base_path($cookie . '.txt'));
        }

        $url = "https://passport.yandex.ru/auth?";
        $pageCont = $this->browserGetContents($url, $cookie, $referer);
        $paramArr = $this->parseForm($pageCont);

        $paramArr['login'] = $login;
        $paramArr['hidden-password'] = $password;
        $url = "https://passport.yandex.ru/auth?retpath=https%3A%2F%2Fyandex.ru%2F?";

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
            return 1;
        } else {
            return 0;
        }
    }
}
