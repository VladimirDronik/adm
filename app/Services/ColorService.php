<?php

namespace App\Services;

use App\Models\Color;

class ColorService
{
    public static function getAll()
    {
        return Color::orderBy('id')->get();
    }
}
