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
    const PROP_AUTOMODE   = 'auto';
    const PROP_MANUALMODE = 'manual';
    const PROP_HEAT_TEMP  = 'heat_temp';
    const PROP_WATER_TEMP = 'water_temp';
    const PROP_BURNER     = 'burner';
    const PROP_BURNER_GVS = 'burnerGVS';
    const PROP_MODULATION = 'modulation';
    const PROP_PUMP       = 'pump';
    const PROP_PRESSURE   = 'pressue';

    const DEFAULT_GVS_TEMP = 45;


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

    public static function getModes()
    {
        $properties = [
            self::PROP_AUTOMODE   => 'Автоматический режим',
            self::PROP_MANUALMODE => 'Ручной режим',
        ];

        return $properties;
    }


    public static function getElementsForPage($idPage)
    {
        return [
            ['name' => 'Подача', 'type' => 'label', 'image' => '', 'value' => '60℃',
                'page' =>$idPage,  'parent' => 0, 'position' => 1, 'sort' => 1, 'active' => 1, 'handle' => 'csupply'],
            ['name' => 'Обратка', 'type' => 'label', 'image' => '', 'value' => '45℃',
                'page' =>$idPage,  'parent' => 0, 'position' => 1, 'sort' => 2, 'active' => 1, 'handle' => 'creturn'],
            ['name' => 'Улица', 'type' => 'label', 'image' => '', 'value' => '5℃',
                'page' =>$idPage,  'parent' => 0, 'position' => 1, 'sort' => 3, 'active' => 1, 'handle' => 'temperature'],
            ['name' => 'Состояние', 'type' => 'switch', 'image' => 'boiler.svg', 'value' => 'on',
                'page' =>$idPage,  'parent' => 0, 'position' => 2, 'sort' => 1, 'active' => 1, 'handle' => 'state'],
            ['name' => 'Автоматический режим', 'type' => 'switch', 'image' => 'settings.svg', 'value' => 'on', 'settings' => 'false',
                'page' =>$idPage,  'parent' => 0, 'position' => 2, 'sort' => 2, 'active' => 1, 'handle' => 'automode'],
            ['name' => 'Ручной режим', 'type' => 'switch', 'image' => 'settings.svg', 'value' => 'off', 'settings' => 'true',
                'page' =>$idPage,  'parent' => 0, 'position' => 2, 'sort' => 3, 'active' => 1, 'handle' => 'manualmode'],
            ['name' => 'Состояние насоса', 'type' => 'label', 'image' => 'nasos.svg', 'value' => 'Включено',
                'page' =>$idPage,  'parent' => 0, 'position' => 2, 'sort' => 4, 'active' => 1, 'handle' => 'pump'],
            ['name' => 'Давление теплоносителя, бар', 'type' => 'label', 'image' => 'davlenie.svg', 'value' => '5',
                'page' =>$idPage,  'parent' => 0, 'position' => 2, 'sort' => 5, 'active' => 1, 'handle' => 'pressue'],
        ];
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
