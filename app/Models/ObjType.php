<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ObjType
 *
 * @property int $id
 * @property string $name
 * @property string|null $label
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereName($value)
 * @mixin \Eloquent
 */
class ObjType extends Model
{
    const TYPE_COUNT = 'count';
    const TYPE_TERMOSTAT = 'termostat';

    protected $table = 'objtypes';
    public $timestamps = false;
}
