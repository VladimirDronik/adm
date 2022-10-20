<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;


/**
 * \App\Models\BoilerWater
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device query()
 * @mixin \Eloquent
 *
 * @property int    $id
 * @property int    $id_object
 * @property double $set_value
 * @property double $min_value
 * @property double $max_value
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereType($value)
 */
class BoilerWater extends Model
{
    const MIN_VALUE = 20;
    const MAX_VALUE = 80;

    public $timestamps = false;

    protected $table   = 'boiler_water';
    protected $guarded = ['id'];

    /* relations */

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object');
    }
}
