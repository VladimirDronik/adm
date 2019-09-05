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