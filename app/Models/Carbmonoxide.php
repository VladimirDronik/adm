<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Carbmonoxide
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
 * @property string|null $hight_method_params
 * @property-read \App\Models\HomeObject $eobject
 * @property-read \App\Models\HomeObject|null $iobject
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide query()
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereCalibration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereCurValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereHighMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereHighObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereHighValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereHightMethodParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereLowMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereLowMethodParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereLowObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereLowValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carbmonoxide whereRoom($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Method|null $emethod_low
 * @property-read \App\Models\Method|null $emethod_high
 */
class Carbmonoxide extends Model
{
    protected $table = 'carbmonoxide';
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

