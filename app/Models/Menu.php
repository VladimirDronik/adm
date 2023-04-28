<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menu
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string|null $link
 * @property string $image
 * @property int $sort
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereTitle($value)
 * @mixin \Eloquent
 * @property-read mixed $image_path
 */
class Menu extends Model
{
    protected $table = 'menu';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function getImagePathAttribute()
    {
        return ImageService::MENU_PATH.'/'.$this->image;
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent', 'id');
    }
}
