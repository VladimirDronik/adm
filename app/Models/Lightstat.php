<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Lightstat
 *
 * @package App\Models
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $id_object id светостата из таблицы объектов
 * @property float $current текущая освещенность
 * @property float $optimal значение, которое должно быть в помещении
 * @property float $gisteresis гистерезис
 * @property int $mode 0 - реакция на потемнение, 1 - реакция на посветление
 * @property int|null $object Объект, у которого будем менять состояние
 * @property int|null $method_on Метод объекта при срабатывании светостата на включение
 * @property int|null $method_off Метод объекта при срабатывании светостата на выключение
 * @property string|null $method_on_params
 * @property string|null $method_off_params
 * @property int $min_threshold минимальное значение, которое возможно в помещении
 * @property int $max_threshold максимальное значение, которое возможно в помещении
 * @property int $min_alarm минимальное значение аварии
 * @property int $max_alarm максимальное значение аварии
 * @property int|null $room
 * @property int|null $usensor_id
 * @property string|null $placetype
 * @property-read \App\Models\HomeObject|null $eobject
 * @property-read mixed $rus_lightstat
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereGisteresis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereMaxAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereMaxThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereMethodOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereMethodOffParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereMethodOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereMethodOnParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereMinAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereMinThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereOptimal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat wherePlacetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Lightstat whereUsensorId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Method|null $emethod_off
 * @property-read \App\Models\Method|null $emethod_on
 */

class Lightstat extends Model
{
    protected $table = 'lightstats';
    public $timestamps = false;
    protected $guarded = ['id'];


    public static function getFullLigtstatIds()
    {
        return [
            0 => 'Реакция на потемнение',
            1 => 'Реакция на посветление',
        ];
    }

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public static function getLightstatById($id) {
        return self::getFullLigtstatIds()[$id] ?? '';
    }

    public function getRusLightstatAttribute()
    {
        return self::getLightstatById($this->mode);
    }

    public function emethod_on()
    {
        return $this->belongsTo(Method::class, 'method_on', 'id');
    }

    public function emethod_off()
    {
        return $this->belongsTo(Method::class, 'method_off', 'id');
    }
}
