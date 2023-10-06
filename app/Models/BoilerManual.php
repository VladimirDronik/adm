<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * \App\Models\BoilerManual
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device query()
 *
 * @mixin \Eloquent
 *
 * @property int    $id
 * @property int    $id_object
 * @property float $set_value
 * @property float $min_value
 * @property float $max_value
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereType($value)
 */
class BoilerManual extends Model
{
    const MIN_VALUE = 20;

    const MAX_VALUE = 80;

    const DEFAULT_SET_VALUE = 55;

    public $timestamps = false;

    protected $table = 'boiler_manual';

    protected $guarded = ['id'];

    /* relations */

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object');
    }
}
