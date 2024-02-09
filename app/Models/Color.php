<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Color
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color query()
 *
 * @mixin \Eloquent
 *
 * @property int $id
 * @property string $name
 * @property string $color
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Color whereName($value)
 */
class Color extends Model
{
    const NAME_TYPE = 'name';
    const HSV_TYPE = 'hsv';

    public $timestamps = false;
    protected $guarded = ['id'];
}
