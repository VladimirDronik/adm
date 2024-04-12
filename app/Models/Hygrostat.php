<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Hygrostat
 *
 * @property int $id
 * @property int|null $id_object id датчика температуры из таблицы объектов
 * @property float $current текущая температура
 * @property float $optimal значение, которое должно быть в помещении
 * @property float $gisteresis гистерезис
 * @property int $thermostat 0 - охлаждение, 1 - нагрев.
 * @property int|null $object Объект, у которого будем менять состояние
 * @property int|null $method_on Метод объекта при срабатывании датчика температуры на включение
 * @property int|null $method_off Метод объекта при срабатывании датчика температуры на выключение
 * @property int|null $id_device id девайса из таблицы devices на котором висит термометр
 * @property int|null $port номер порта мега, на котором висит датчик температуры
 * @property int $min_threshold минимальное значение, которое возможно в помещении
 * @property int $max_threshold максимальное значение, которое возможно в помещении
 * @property int $min_alarm минимальное значение аварии
 * @property int $max_alarm максимальное значение аварии
 * @property int|null $room
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereGisteresis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereIdDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereMaxAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereMaxThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereMethodOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereMethodOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereMinAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereMinThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereOptimal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereThermostat($value)
 *
 * @mixin \Eloquent
 *
 * @property-read \App\Models\HomeObject|null $eobject
 * @property-read \App\Models\Room|null $eroom
 * @property-read \App\Models\Device|null $edevice
 * @property-read \App\Models\Method|null $emethod_off
 * @property-read \App\Models\Method|null $emethod_on
 * @property-read mixed $rus_hygrostat
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GraphHygrostat[] $graphs
 * @property-read \App\Models\HomeObject|null $iobject
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GraphHygrostat[] $last_graphs
 * @property string|null $name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereName($value)
 *
 * @property string|null $method_on_params
 * @property string|null $method_off_params
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereMethodOffParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereMethodOnParams($value)
 *
 * @property int|null $usensor_id
 * @property string|null $placetype
 * @property-read int|null $graphs_count
 * @property-read int|null $last_graphs_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat wherePlacetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hygrostat whereUsensorId($value)
 */
class Hygrostat extends Model
{
    const HYGROSTAT_DRY = 0;

    const HYGROSTAT_WET = 1;

    protected $table = 'hygrostats';

    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getFullHygrostatIds()
    {
        return [
            self::HYGROSTAT_DRY => 'Осушение',
            self::HYGROSTAT_WET => 'Увлажнение',
        ];
    }

    public static function getHygrostatIds()
    {
        return array_keys(self::getFullHygrostatIds());
    }

    public static function getHygrostatById($id)
    {
        return self::getFullHygrostatIds()[$id] ?? '';
    }

    public function getRusHygrostatAttribute()
    {
        return self::getHygrostatById($this->type);
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

    public function emethod_on()
    {
        return $this->belongsTo(Method::class, 'method_on', 'id');
    }

    public function emethod_off()
    {
        return $this->belongsTo(Method::class, 'method_off', 'id');
    }

    public function last_graphs()
    {
        return $this->hasMany(GraphHumidity::class, 'id_hygrostat', 'id')->where('datetime', '>=', Carbon::now()->subDays(7))
            ->orderBy('datetime');
    }
}
