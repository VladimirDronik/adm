<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Property
 *
 * @property int $id
 * @property string $name
 * @property int $id_object
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Property whereValue($value)
 * @mixin \Eloquent
 */
class Property extends Model
{
    protected $table = 'propertyes';
    public $timestamps = false;
}
