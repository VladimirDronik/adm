<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lamp extends Model
{
    const TYPE_LAMP = 'lamp';

    const TYPE_DIMER = 'dimer';

    protected $table = 'lamps';

    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_LAMP => 'Лампа',
            self::TYPE_DIMER => 'Диммер',
        ];

        return $is_full ? $types : array_keys($types);
    }

    public static function getEvents()
    {
        return [
            'onStatusOn' => 'Включение',
            'onStatusOff' => 'Выключение',
        ];
    }

    public function getMethodsAliasByType(): array
    {
        $methodsAlias = [
            self::TYPE_LAMP => ['lamp_on', 'lamp_off', 'lamp_switch'],
            self::TYPE_DIMER => ['dimmer_on', 'dimmer_off', 'dimmer_up', 'dimmer_down', 'dimmer_set'],
        ];

        return array_key_exists($this->type, $methodsAlias) ? $methodsAlias[$this->type] : [];
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

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
