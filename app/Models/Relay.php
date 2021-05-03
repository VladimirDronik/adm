<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Relay
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int|null $id_object
 * @property-read mixed $rus_type
 * @property-read \App\Models\HomeObject|null $object
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay whereType($value)
 * @mixin \Eloquent
 */
class Relay extends Model
{
    const TYPE_RELAY = 'relay';
    const TYPE_SOCKET = 'socket';

    protected $table = 'relays';
    public $timestamps = false;

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_RELAY => 'Реле',
            self::TYPE_SOCKET => 'Розетка'
        ];

        return $is_full ? $types : array_keys($types);
    }

    /**
     * Получение доступных событий для объекта
     * @return array
     */
    public static function getEvents()
    {
        return [
            'onStatus' => 'Смена статуса',
        ];
    }

    /**
     * Получение доступных свойств объекта.
     * Формат: 'название_свойтсва' => ['Описание на русском', 'доступно для чтения', 'доступно для записи']
     * @return array
     */
    public static function getProperties()
    {
        return [
            'status' => ['Статус, on/off', true, true],
        ];
    }

    public function getRusTypeAttribute()
    {
        return self::getTypes(true)[$this->type] ?? '';
    }

    /* relations */

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
