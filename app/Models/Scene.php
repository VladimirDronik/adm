<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Scene
 *
 * @property int $id
 * @property string $name
 * @property string $label
 * @property string $image
 * @property string $backgroung_color
 * @property int $sort
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereBackgroungColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereSort($value)
 * @mixin \Eloquent
 */
class Scene extends Model
{
    protected $table = 'scenes';
    public $timestamps = false;
}
