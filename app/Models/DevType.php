<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DevType
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Devtype newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Devtype newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Devtype query()
 * @mixin \Eloquent
 */
class DevType extends Model
{
    protected $table = 'devtypes';
    public $timestamps = false;
}
