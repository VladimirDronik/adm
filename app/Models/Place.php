<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Place
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place whereName($value)
 * @mixin \Eloquent
 */
class Place extends Model
{
    public $timestamps = false;
}
