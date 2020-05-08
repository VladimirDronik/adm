<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Dimmer
 *
 * @property int $id
 * @property string $name
 * @property int|null $id_object
 * @property int $value
 * @property int $speed
 * @property-read \App\Models\HomeObject|null $object
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dimmer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dimmer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dimmer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dimmer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dimmer whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dimmer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dimmer whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dimmer whereValue($value)
 * @mixin \Eloquent
 * @property int|null $oldvalue
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Dimmer whereOldvalue($value)
 */
class Dimmer extends Model
{
    protected $table = 'dimmers';
    public $timestamps = false;

    /* relations */

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
