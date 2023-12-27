<?php

namespace App\Services;

use App\Models\Color;

class ColorService
{
    public static function getAllByType(string $type = Color::NAME_TYPE)
    {
        return Color::where('type', $type)->orderBy('id')->get();
    }
}
