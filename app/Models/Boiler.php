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
    const PROP_CSUPPLY    = 'csupply';
    const PROP_CRETURN    = 'creturn';
    const PROP_STATE      = 'state';
    const PROP_AUTOMODE   = 'automode';
    const PROP_MANUALMODE = 'manualmode';
    const PROP_HEAT_TEMP  = 'heat_temp';
    const PROP_WATER_TEMP = 'water_temp';
    const PROP_BURNER     = 'burner';
    const PROP_BURNER_GVS = 'burnerGVS';
    const PROP_MODULATION = 'modulation';
    const PROP_PUMP       = 'pump';
    const PROP_PRESSURE   = 'pressue';


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
            self::PROP_CSUPPLY    => 'Температура подачи',
            self::PROP_CRETURN    => 'Температура обратки',
            self::PROP_STATE      => 'Состояние котла',
            self::PROP_AUTOMODE   => 'Автоматический режим',
            self::PROP_MANUALMODE => 'Ручной режим',
            self::PROP_HEAT_TEMP  => 'Установленная температура котла',
            self::PROP_WATER_TEMP => 'Установленная температура воды',
//          self::PROP_BURNER     => 'Состояние горелки',
//          self::PROP_BURNER_GVS => 'Состояние горелки ГВС',
//          self::PROP_MODULATION => 'Модуляция',
//          self::PROP_PUMP       => 'Состояние насоса',
            self::PROP_PRESSURE   => 'Давление'
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
