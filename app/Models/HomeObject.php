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
 * @property int $id
 * @property string $name название объекта
 * @property string $type
 * @property string $status
 * @property int|null $view
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereView($value)
 * @property-read \App\Models\View|null $eview
 * @property-read mixed $rus_type
 */
class HomeObject extends Model
{
    const TYPE_BUTTON = 'button';
    const TYPE_LAMP = 'lamp';

    protected $table = 'objects';
    public $timestamps = false;

    public static function getFullTypeIds()
    {
        return [
            self::TYPE_BUTTON => 'Кнопка',
            self::TYPE_LAMP => 'Лампа',
        ];
    }

    public static function getTypeIds()
    {
        return array_keys(self::getFullTypeIds());
    }

    public static function getTypeById($id) {
        return self::getFullTypeIds()[$id] ?? '';
    }

    public function getRusTypeAttribute()
    {
        return self::getTypeById($this->type);
    }

    /* relations */

    public function eview()
    {
        return $this->belongsTo(View::class, 'view', 'id');
    }
}
