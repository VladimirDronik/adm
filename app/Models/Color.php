<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Color
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $color
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color whereName($value)
 */
class Color extends Model
{
    const GREY = 'grey';
    const BLUE = 'blue';
    const ORANGE = 'orange';
    const RED = 'red';
    const GREEN = 'green';
    const PURPLE = 'purple';
    const TURQUOISE = 'turquoise';
    const LIGHT_GREEN = 'lightGreen';
    const YELLOW = 'yellow';
    const GOLD = 'gold';

    public $timestamps = false;

    public static function getColors(bool $is_full = true)
    {
        if ($is_full) {
            return [
                self::GREY => '#656565',
                self::BLUE => '#0060aa',
                self::ORANGE => '#f36f21',
                self::RED => '#ff0000',
                self::GREEN => '#007439',
                self::PURPLE => '#C73C93',
                self::TURQUOISE => '#328F9D',
                self::LIGHT_GREEN => '#7EDF44',
                self::YELLOW => '#EEFB4C',
                self::GOLD => '#FFD700'
            ];
        }

        return array_keys(self::getColors());
    }

    public static function getStyleByColor($color)
    {
        if (empty($color)) {
            return '';
        }

        return self::getColors()[$color] ?? '';
    }
}
