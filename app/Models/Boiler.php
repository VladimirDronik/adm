<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Boiler
 *
 * @mixin \Eloquent
 *
 * @property-read mixed $rus_type
 */
class Boiler extends Model
{
    const PROP_CSUPPLY = 'csupply';
    const PROP_CRETURN = 'creturn';
    const PROP_STATE = 'state';
    const PROP_AUTOMODE = 'auto';
    const PROP_MANUALMODE = 'manual';
    const PROP_HEAT_TEMP = 'heat_temp';
    const PROP_WATER_TEMP = 'water_temp';
    const PROP_BURNER = 'burner';
    const PROP_BURNER_GVS = 'burnerGVS';
    const PROP_MODULATION = 'modulation';
    const PROP_PUMP = 'pump';
    const PROP_PRESSURE = 'pressure';

    const TYPE_ELECTRO = 'electro';
    const TYPE_GAS = 'gas';

    const MODE_CH_DHW = 'ch_dhw';
    const MODE_CH = 'ch';
    const MODE_DHW = 'dhw';

    const HEATING_MODE_MANUAL = 'manual';
    const HEATING_MODE_WC = 'wc';

    const DEFAULT_GVS_TEMP = 45;

    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getTypes(bool $is_full = true)
    {
        $types = [
            static::TYPE_ELECTRO => 'Электрический',
            static::TYPE_GAS => 'Газовый',
        ];

        return $is_full ? $types : array_keys($types);
    }

    public static function getModes(bool $is_full = true)
    {
        $modes = [
            static::MODE_CH_DHW => 'Отопление и ГВС',
            static::MODE_CH => 'Отопление',
            static::MODE_DHW => 'ГВС',
        ];

        return $is_full ? $modes : array_keys($modes);
    }

    public static function getHeatingModes(bool $is_full = true)
    {
        $modes = [
            static::HEATING_MODE_MANUAL => 'Ручной',
            static::HEATING_MODE_WC => 'Погодозависимый',
        ];

        return $is_full ? $modes : array_keys($modes);
    }

    public static function getExchangeProtocols(bool $is_full = true)
    {
        $protocols = [
            'ebus' => 'ebus',
            'openterm' => 'openterm',
        ];

        return $is_full ? $protocols : array_keys($protocols);
    }

    public static function getProperties()
    {
        $properties = [
            self::PROP_CSUPPLY => 'Температура подачи',
            self::PROP_CRETURN => 'Температура обратки',
            self::PROP_STATE => 'Состояние котла',
            self::PROP_AUTOMODE => 'Автоматический режим',
            self::PROP_MANUALMODE => 'Ручной режим',
            self::PROP_HEAT_TEMP => 'Установленная температура котла',
            self::PROP_WATER_TEMP => 'Установленная температура воды',
            //          self::PROP_BURNER     => 'Состояние горелки',
            //          self::PROP_BURNER_GVS => 'Состояние горелки ГВС',
            //          self::PROP_MODULATION => 'Модуляция',
            //          self::PROP_PUMP       => 'Состояние насоса',
            self::PROP_PRESSURE => 'Давление',
        ];

        return $properties;
    }

    public static function getElementsForPage($idPage)
    {
        return [
            ['name' => 'Подача', 'type' => 'label', 'image' => '', 'value' => '60℃',
                'page' => $idPage,  'parent' => 0, 'position' => 1, 'sort' => 1, 'active' => 1, 'handle' => 'csupply'],
            ['name' => 'Обратка', 'type' => 'label', 'image' => '', 'value' => '45℃',
                'page' => $idPage,  'parent' => 0, 'position' => 1, 'sort' => 2, 'active' => 1, 'handle' => 'creturn'],
            ['name' => 'Улица', 'type' => 'label', 'image' => '', 'value' => '5℃',
                'page' => $idPage,  'parent' => 0, 'position' => 1, 'sort' => 3, 'active' => 1, 'handle' => 'temperature'],
            ['name' => 'Состояние', 'type' => 'switch', 'image' => 'boiler.svg', 'value' => 'on',
                'page' => $idPage,  'parent' => 0, 'position' => 2, 'sort' => 1, 'active' => 1, 'handle' => 'state'],
            ['name' => 'Автоматический режим', 'type' => 'switch', 'image' => 'settings.svg', 'value' => 'on', 'settings' => 'false',
                'page' => $idPage,  'parent' => 0, 'position' => 2, 'sort' => 2, 'active' => 1, 'handle' => 'automode'],
            ['name' => 'Ручной режим', 'type' => 'switch', 'image' => 'settings.svg', 'value' => 'off', 'settings' => 'true',
                'page' => $idPage,  'parent' => 0, 'position' => 2, 'sort' => 3, 'active' => 1, 'handle' => 'manualmode'],
            ['name' => 'Состояние насоса', 'type' => 'label', 'image' => 'nasos.svg', 'value' => 'Включено',
                'page' => $idPage,  'parent' => 0, 'position' => 2, 'sort' => 4, 'active' => 1, 'handle' => 'pump'],
            ['name' => 'Давление теплоносителя, бар', 'type' => 'label', 'image' => 'davlenie.svg', 'value' => '5',
                'page' => $idPage,  'parent' => 0, 'position' => 2, 'sort' => 5, 'active' => 1, 'handle' => 'pressure'],
        ];
    }

    public function getRusTypeAttribute()
    {
        return static::getTypes(true)[$this->type] ?? '';
    }

    public function getProtocolBySlaverAttribute()
    {
        $protocol = '';

        switch ($this->modbusSlaver?->relatedType->type) {
            case 'bcg-301-w':
                $protocol = 'opentherm';
                break;
            case 'beg-311-w':
                $protocol = 'ebus';
                break;
            default:
                $protocol = '';
                break;
        }

        return $protocol;
    }

    public function object(): BelongsTo
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function outdoorSensor(): BelongsTo
    {
        return $this->belongsTo(Termostat::class, 'outdoor_sensor', 'id_object');
    }

    public function indoorSensor(): BelongsTo
    {
        return $this->belongsTo(Termostat::class, 'indoor_sensor', 'id_object');
    }

    public function modbusSlaver(): BelongsTo
    {
        return $this->belongsTo(ModbusSlaver::class, 'gateway_id', 'id');
    }
}
