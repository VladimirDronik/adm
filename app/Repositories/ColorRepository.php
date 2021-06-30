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
     * Отдать в массиве доступные цвета для кнопок
     * @return mixed
     */
     public static function getColors()
     {
         return Color::select('id','name')->orderBy('name')->pluck('name', 'id')->toArray();
     }

}