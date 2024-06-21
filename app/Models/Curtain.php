<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Curtain
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int|null $id_object
 * @property-read mixed $rus_type
 * @property-read \App\Models\HomeObject|null $object
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Curtain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Curtain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Curtain query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Curtain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Curtain whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Curtain whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Curtain whereType($value)
 *
 * @mixin \Eloquent
 */
class Curtain extends Model
{
    const TYPE_CURTAIN = 'curtain';

    const TYPE_ROLLER = 'roller';

    const PLACE_PORT = 'port';

    const PLACE_PHASE = 'phase';

    const PLACE_RS485 = 'rs485';

    protected $table = 'curtains';

    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_CURTAIN => 'Раздвижной',
            self::TYPE_ROLLER => 'Рулонный',
        ];

        return $is_full ? $types : array_keys($types);
    }

    public static function getPlaces(bool $is_full = false)
    {
        $places = [
            self::PLACE_PORT => 'Сухой контакт',
            self::PLACE_PHASE => 'Фазное управление',
            self::PLACE_RS485 => 'RS-485',
        ];

        return $is_full ? $places : array_keys($places);
    }

    /**
     * Получение всех методов для объекта
     *
     * @return array
     */
    public static function getEvents()
    {
        return [
            'onStatusOn' => 'Открытие',
            'onStatusOff' => 'Закрытие',
        ];
    }

    /**
     * Получение доступных свойств объекта.
     * Формат: 'название_свойтсва' => ['Описание на русском', 'доступно для чтения', 'доступно для записи']
     *
     * @return array
     */
    public static function getProperties()
    {
        return [
            'status' => ['Статус, open/close', true, true],
        ];
    }

    public function getRusTypeAttribute()
    {
        return self::getTypes(true)[$this->type] ?? '';
    }

    public function getRusPlaceAttribute()
    {
        return self::getPlaces(true)[$this->place] ?? '';
    }

    /* relations */

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
