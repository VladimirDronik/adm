<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Room
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $style
 * @property int $sort
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereStyle($value)
 * @property int $lighting
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Termostat[] $termostats
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereLighting($value)
 * @property-read mixed $color_style
 * @property-read \App\Models\Temperature $temperature
 */
class Room extends Model
{
    const COMMON_NAME = 'Общие';
    public $timestamps = false;

    /* attributes */

    public function getColorStyleAttribute()
    {
        return Color::getStyleByColor($this->style);
    }

    /* relations */

    public function termostats()
    {
        return $this->hasMany(Termostat::class, 'room', 'id')->orderBy('id');
    }

    public function temperature()
    {
        return $this->hasOne(Temperature::class, 'id_room');
    }
}
