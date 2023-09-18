<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Scene
 *
 * @property int $id
 * @property string $name
 * @property string $label
 * @property string $image
 * @property string $background_color
 * @property int $sort
 * @property int $active
 *
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
 *
 * @mixin \Eloquent
 *
 * @property-read mixed $image_path
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereBackgroundColor($value)
 */
class Scene extends Model
{
    protected $table = 'scenes';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function getImagePathAttribute()
    {
        return ImageService::SCENE_PATH.'/'.$this->image;
    }
}
