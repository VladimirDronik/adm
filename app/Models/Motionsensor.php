<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Motionsensor
 *
 * @property int $id
 * @property int|null $id_object id датчика из таблицы объектов
 * @property string $name
 * @property int|null $method_normal Метод при нормальном режиме
 * @property int|null $method_eco Метод при эко режиме
 * @property int|null $method_night Метод при ночном режиме
 * @property int|null $method_morning Метод при утреннем режиме
 * @property int|null $method_evening Метод при вечернем режиме
 * @property int|null $method_guard Метод при режиме охраны
 * @property int|null $lightstat Светостат с которым сравнивается значение
 * @property string|null $equality Знак сравнения значения
 * @property int|null $lightvalue Значение с которым сравниваем значение светостата
 * @property int|null $method_light Метод при пороговом значении освещенности
 * @property-read \App\Models\HomeObject|null $iobject
 * @property-read \App\Models\HomeObject|null $object
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereEquality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereLightstat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereLightvalue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereMethodEco($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereMethodEvening($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereMethodGuard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereMethodLight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereMethodMorning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereMethodNight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereMethodNormal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motionsensor whereName($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Method|null $emethod_eco
 * @property-read \App\Models\Method|null $emethod_evening
 * @property-read \App\Models\Method|null $emethod_guard
 * @property-read \App\Models\Method|null $emethod_light
 * @property-read \App\Models\Method|null $emethod_morning
 * @property-read \App\Models\Method|null $emethod_night
 * @property-read \App\Models\Method|null $emethod_normal
 */
class Motionsensor extends Model
{
    protected $table = 'motionsensors';
    public $timestamps = false;
    protected $guarded = ['id'];

    public static function getEvents()
    {
        return [
            'onGuadActivation' => 'Срабатывание в режиме охраны',
            'onNormalActivation' => 'Срабатывание в нормальном режиме',
            'onEcoActivation' => 'Срабатывание в эко режиме',
            'onNightActivation' => 'Срабатывание в ночном режиме',
            'onMorningActivation' => 'Срабатывание в утреннем режиме',
            'onEveningActivation' => 'Срабатывание в вечернем режиме',
            'onAnyActivation' => 'Срабатывание в любом режиме',
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
            'relateLight' => ['Значение связанного светостата', true, true],
            'mode' => ['Текущий режим', true, true],
        ];
    }

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function emethod_eco()
    {
        return $this->belongsTo(Method::class, 'method_eco', 'id');
    }

    public function emethod_evening()
    {
        return $this->belongsTo(Method::class, 'method_evening', 'id');
    }

    public function emethod_guard()
    {
        return $this->belongsTo(Method::class, 'method_guard', 'id');
    }

    public function emethod_light()
    {
        return $this->belongsTo(Method::class, 'method_light', 'id');
    }

    public function emethod_morning()
    {
        return $this->belongsTo(Method::class, 'method_morning', 'id');
    }

    public function emethod_night()
    {
        return $this->belongsTo(Method::class, 'method_night', 'id');
    }

    public function emethod_normal()
    {
        return $this->belongsTo(Method::class, 'method_normal', 'id');
    }
}
