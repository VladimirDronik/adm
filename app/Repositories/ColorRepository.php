<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 28.06.21
 * Time: 18:22
 */

namespace App\Repositories;


use App\Models\Color;

class ColorRepository
{

    /**
     * Отдать в массиве доступные цвета по name типу
     * @return mixed
     */
    public static function getNameTypeColors()
    {
        return Color::where('type', Color::NAME_TYPE)
            ->select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Отдать в массиве доступные цвета по hsv типу
     * @return mixed
     */
    public static function getHsvTypeColors()
    {
        return Color::where('type', Color::HSV_TYPE)
            ->select('id', 'value')
            ->orderBy('value')
            ->pluck('value', 'id')
            ->toArray();
    }
}