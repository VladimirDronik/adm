<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Boiler
 *
 * @package App\Models
 * @mixin \Eloquent
 * @property-read mixed $rus_type
 */
class Boiler extends Model
{


    protected $table = 'boiler';
    public $timestamps = false;
    protected $guarded = ['id'];



    public static function getTypes(bool $is_full = true)
    {
        $types = [
            'ebus' => 'ebus',
            'openterm' => 'openterm'
        ];

        return $is_full ? $types : array_keys($types);
    }

    public static function getProperties()
    {
        $properties = [
            'csupply' => 'Температура подачи',
            'creturn' => 'Температура обратки',
            'state' => 'Состояние котла',
            'automode' => 'Автоматический режим',
            'heat_temp' => 'Установленная температура котла',
            'water_temp' => 'Установленная температура воды',
//            'burner' => 'Состояние горелки',
//            'burnerGVS' => 'Состояние горелки ГВС',
//            'modulation' => 'Модуляция',
//            'pump' => 'Состояние насоса',
            'pressue' => 'Давление'
        ];

        return $properties;
    }


    public function getRusTypeAttribute()
    {
        return self::getTypes(true)[$this->type] ?? '';
    }


    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
