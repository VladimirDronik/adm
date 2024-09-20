<?php

namespace App\Repositories;

use App\Models\Color;

class ColorRepository
{
    /**
     * Отдать в массиве доступные цвета по name типу
     */
    public static function getNameTypeColors(): array
    {
        return Color::where('type', Color::NAME_TYPE)
            ->select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Отдать в массиве доступные цвета по hsv типу
     */
    public static function getHsvTypeColors(): array
    {
        return Color::where('type', Color::HSV_TYPE)
            ->select('id', 'value')
            ->orderBy('value')
            ->pluck('value', 'id')
            ->toArray();
    }
}
