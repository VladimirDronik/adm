<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\HomeObject
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject query()
 * @mixin \Eloquent
 */
class HomeObject extends Model
{
    protected $table = 'objects';
    public $timestamps = false;
}
