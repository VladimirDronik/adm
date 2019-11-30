<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Count
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int|null $id_object
 * @property int $impulse
 * @property string $unit
 * @property int $today_value
 * @property int $total_value
 * @property-read \App\Models\HomeObject|null $object
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count whereImpulse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count whereTodayValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count whereTotalValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Count whereUnit($value)
 * @mixin \Eloquent
 */
class Count extends Model
{
    const TYPE_WATER = 'water';
    const TYPE_ELECTRO = 'electro';

    protected $table = 'counts';
    public $timestamps = false;

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_WATER => 'Вода',
            self::TYPE_ELECTRO => 'Электричество'
        ];

        return $is_full ? $types : array_keys($types);
    }

    public function getRusTypeAttribute()
    {
        return self::getTypes(true)[$this->type] ?? '';
    }

    public function getImageAttribute()
    {
        return $this->type.'.png';
    }

    /* relations */

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
