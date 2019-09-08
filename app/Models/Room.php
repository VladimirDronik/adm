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
 */
class Room extends Model
{
    const COMMON_NAME = 'Общие';
    public $timestamps = false;

    /* relations */

    public function termostats()
    {
        return $this->hasMany(Termostat::class, 'room', 'id')->orderBy('id');
    }
}
