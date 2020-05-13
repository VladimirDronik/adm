<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Temperature
 *
 * @property int $id
 * @property int $id_room
 * @property float $normal
 * @property float $night
 * @property float $eco
 * @property int $sort
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Temperature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Temperature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Temperature query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Temperature whereEco($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Temperature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Temperature whereIdRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Temperature whereNight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Temperature whereNormal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Temperature whereSort($value)
 * @mixin \Eloquent
 */
class Temperature extends Model
{
    protected $table = 'temperatures';
    public $timestamps = false;



}
