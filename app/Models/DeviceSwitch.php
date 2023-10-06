<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DeviceSwitch
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int|null $id_object
 * @property-read mixed $rus_type
 * @property-read \App\Models\HomeObject|null $object
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceSwitch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceSwitch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceSwitch query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceSwitch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceSwitch whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceSwitch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeviceSwitch whereType($value)
 *
 * @mixin \Eloquent
 */
class DeviceSwitch extends Model
{
    const TYPE_BUTTON = 'button';

    const TYPE_SWITCH = 'switch';

    protected $table = 'switches';

    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_BUTTON => 'Кнопка',
            self::TYPE_SWITCH => 'Выключатель',
        ];

        return $is_full ? $types : array_keys($types);
    }

    /**
     * Получение всех методов для объекта
     *
     * @return array
     */
    public static function getEvents()
    {
        return [
            'onStatusOn' => 'Включение',
            'onStatusOff' => 'Выключение',
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
