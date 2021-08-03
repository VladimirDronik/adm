<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Manometr
 *
 * @property int $id
 * @property string $name
 * @property int|null $id_object
 * @property int $cur_value
 * @property int $low_value
 * @property int|null $low_object
 * @property int|null $low_method
 * @property int $high_value
 * @property int|null $high_object
 * @property int|null $high_method
 * @property float $calibration
 * @property int|null $room
 * @property string|null $low_method_params
 * @property string|null $high_method_params
 * @property-read \App\Models\HomeObject $eobject
 * @property-read \App\Models\HomeObject|null $iobject
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr query()
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereCalibration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereCurValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereHighMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereHighMethodParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereHighObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereHighValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereLowMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereLowMethodParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereLowObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereLowValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Manometr whereRoom($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Method|null $emethod_low
 * @property-read \App\Models\Method|null $emethod_high
 */
class Manometr extends Model
{
    protected $table = 'manometr';
    public $timestamps = false;
    protected $guarded = ['id'];

    public static function getEvents()
    {
        return [
            'onCheck' => 'Проверка датчика',
            'onLowThreshold' => 'Нижнее пороговое значение',
            'onHighThreshold' => 'Верхнее пороговое значение',
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
            'value' => ['Значение датчика', true, true],
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

    public function emethod_low()
    {
        return $this->belongsTo(Method::class, 'low_method', 'id');
    }

    public function emethod_high()
    {
        return $this->belongsTo(Method::class, 'high_method', 'id');
    }
}
