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
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Relay whereType($value)
 *
 * @mixin \Eloquent
 */
class Relay extends Model
{
    const TYPE_RELAY = 'relay';

    protected $table = 'relays';

    public $timestamps = false;

    protected $guarded = ['id'];

    /**
     * Получение доступных событий для объекта
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

    public function getGatewayNameAttribute(): string
    {
        $name = '';

        if ($this->gateway_type == HomeObject::GATEWAY_MODBUS) {
            $slaver = ModbusSlaver::find($this->gateway_id);

            if ($slaver) {
                $name = $slaver->name;
            }
        } else {
            $device = Device::find($this->gateway_id);

            if ($device) {
                $name = $device->description;
            }
        }

        return $name;
    }

    /* relations */

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
