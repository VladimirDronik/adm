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
