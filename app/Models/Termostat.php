<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Termostat
 *
 * @property int $id
 * @property int|null $id_object id термостата из таблицы объектов
 * @property float $current текущая температура
 * @property float $optimal значение, которое должно быть в помещении
 * @property float $gisteresis гистерезис
 * @property int $thermostat 0 - охлаждение, 1 - нагрев.
 * @property int|null $object Объект, у которого будем менять состояние
 * @property int|null $method_on Метод объекта при срабатывании термостата на включение
 * @property int|null $method_off Метод объекта при срабатывании термостата на выключение
 * @property int|null $id_device id девайса из таблицы devices на котором висит термометр
 * @property int|null $port номер порта мега, на котором висит термостат
 * @property string $id_termometr id термометра для идентификации его по коду
 * @property int $min_threshold минимальное значение, которое возможно в помещении
 * @property int $max_threshold максимальное значение, которое возможно в помещении
 * @property int $min_alarm минимальное значение аварии
 * @property int $max_alarm максимальное значение аварии
 * @property int|null $room
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereGisteresis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereIdDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereIdTermometr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereMaxAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereMaxThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereMethodOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereMethodOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereMinAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereMinThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereOptimal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Termostat whereThermostat($value)
 * @mixin \Eloquent
 * @property-read \App\Models\HomeObject|null $eobject
 * @property-read \App\Models\Room|null $eroom
 * @property-read \App\Models\Device|null $edevice
 * @property-read \App\Models\Method|null $emethod_off
 * @property-read \App\Models\Method|null $emethod_on
 * @property-read mixed $rus_thermostat
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Graph[] $graphs
 * @property-read \App\Models\HomeObject|null $iobject
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Graph[] $last_graphs
 */
class Termostat extends Model
{
    const THERMOSTAT_COLD = 0;
    const THERMOSTAT_HOT = 1;

    protected $table = 'termostats';
    public $timestamps = false;
    protected $guarded = ['id'];

    public static function getFullThermostatIds()
    {
        return [
            self::THERMOSTAT_COLD => 'Охлаждение',
            self::THERMOSTAT_HOT => 'Нагревание',
        ];
    }

    public static function getThermostatIds()
    {
        return array_keys(self::getFullThermostatIds());
    }

    public static function getThermostatById($id) {
        return self::getFullThermostatIds()[$id] ?? '';
    }

    public function getRusThermostatAttribute()
    {
        return self::getThermostatById($this->thermostat);
    }

    /* relations */

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function eroom()
    {
        return $this->belongsTo(Room::class, 'room', 'id');
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function edevice()
    {
        return $this->belongsTo(Device::class, 'id_device', 'id');
    }

    public function emethod_on()
    {
        return $this->belongsTo(Method::class, 'method_on', 'id');
    }

    public function emethod_off()
    {
        return $this->belongsTo(Method::class, 'method_off', 'id');
    }

    public function graphs()
    {
        return $this->hasMany(GraphTermostat::class, 'id_termostat', 'id')->orderBy('datetime');
    }

    public function last_graphs()
    {
        return $this->hasMany(GraphTermostat::class, 'id_termostat', 'id')
            ->orderBy('datetime');
    }
}
