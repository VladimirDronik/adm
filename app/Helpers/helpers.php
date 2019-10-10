<?php

/* auth */

if (!function_exists('user')) {
    function user()
    {
        return Auth::user();
    }
}

if (!function_exists('id')) {
    function id()
    {
        return Auth::id();
    }
}

if (!function_exists('logout')) {
    function logout()
    {
        Auth::logout();
    }
}
/* request */

if (!function_exists('ajaxHas')) {
    function ajaxHas(\Illuminate\Http\Request $r, $names) {
        if (!$r->ajax()) {
            return false;
        }
        
        foreach ($names as $name) {
            if (!$r->has($name)) {
                return false;
            }
        }
        
        return true;
    }
}

/* array */

if (!function_exists('trimArray')) {
    function trimArray(&$array, $keys = []) {
        if (empty($keys)) {
            foreach ($array as &$value) {
                $value = trim($value);
            }
        } else {
            foreach ($keys as $key) {
                $array[$key] = trim($array[$key]);
            }
        }
    }
}

/* datetime */

if (!function_exists('daysToShortRus')) {
    function daysToShortRus(string $days) {
        if (empty($days)) {
            return '';
        }
        $a = explode(',',$days);
        $rus_days = '';
        foreach ($a as $k) {
            if (!empty($k) && $k == (int)$k) {
                switch ($k) {
                    case '1': $rus_days .= 'Пн'; break;
                    case '2': $rus_days .= 'Вт'; break;
                    case '3': $rus_days .= 'Ср'; break;
                    case '4': $rus_days .= 'Чт'; break;
                    case '5': $rus_days .= 'Пт'; break;
                    case '6': $rus_days .= 'Сб'; break;
                    case '7': $rus_days .= 'Вс'; break;
                }
                $rus_days .= ', ';
            }
        }
        return trim($rus_days,", ");
    }
}

if (!function_exists('getRusMonth')) {

    function getRusMonth($month = null)
    {
        if (empty($month)) {
            $month = (int)date('m');
        }

        $rus_months = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль',
            'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];

        return $rus_months[$month-1];
    }
}

if (!function_exists('translitRussian')) {
    function translitRussian(string $text)
    {
        if (empty($text)) {
            return '';
        }

        $conv = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
            'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
            'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch',
            'Ш' => 'Sh', 'Щ' => 'Sch', 'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        ];

        return strtr($text, $conv);
    }
}