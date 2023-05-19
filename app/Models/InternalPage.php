<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\InternalPage
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device query()
 * @mixin \Eloquent
 *
 * @property int    $id
 * @property int    idElement
 * @property string type
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereType($value)
 *
 * @property-read \App\Models\Elements $element
 */
class InternalPage extends Model
{
    public    $timestamps = false;
    protected $guarded    = ['id'];
    protected $table = 'internalPages';

    /* relations */
    public function element()
    {
        return $this->belongsTo(Elements::class, 'idElement');
    }
}
