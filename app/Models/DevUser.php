<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DevUser
 *
 * @property int $id
 * @property string $dev_id
 * @property int $def_scene
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevUser whereDefScene($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevUser whereDevId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DevUser whereId($value)
 * @mixin \Eloquent
 */
class DevUser extends Model
{
    protected $table = 'devusers';
    public $timestamps = false;
}
