<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
    const PROP_CH_CURRENT_TEMP = 'ch_current_temp';
    const PROP_CH_SETPOINT_TEMP = 'ch_setpoint_temp';
    const PROP_DHW_CURRENT_TEMP = 'dhw_current_temp';
    const PROP_DHW_SETPOINT_TEMP = 'dhw_setpoint_temp';
    const PROP_RETURN_TEMP = 'return_temp';
    const PROP_ERROR_CODE = 'error_code';
    const PROP_OUTDOOR_TEMP = 'outdoor_temp';
    const PROP_MODULATION = 'modulation';
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

    public function getProperties()
    {
        $paramsFlag = $this->boilersParamsFlag;
        $properties = [];

        if ($paramsFlag->ch_current_temp) {
            $properties[static::PROP_CH_CURRENT_TEMP] = 'Текущая температура ЦО';
        }

        if ($paramsFlag->ch_setpoint_temp) {
            $properties[static::PROP_CH_SETPOINT_TEMP] = 'Уставка ЦО';
        }

        if ($paramsFlag->dhw_current_temp) {
            $properties[static::PROP_DHW_CURRENT_TEMP] = 'Текущая температура ГВС';
        }

        if ($paramsFlag->dhw_setpoint_temp) {
            $properties[static::PROP_DHW_SETPOINT_TEMP] = 'Уставка ГВС';
        }

        if ($paramsFlag->return_temp) {
            $properties[static::PROP_RETURN_TEMP] = 'Температура обратки';
        }

        if ($paramsFlag->modulation) {
            $properties[static::PROP_MODULATION] = 'Модуляция пламени';
        }

        if ($paramsFlag->pressure) {
            $properties[static::PROP_PRESSURE] = 'Давление';
        }

        if ($paramsFlag->error_code) {
            $properties[static::PROP_ERROR_CODE] = 'Наличие ошибки';
        }

        if ($paramsFlag->outdoor_temp) {
            $properties[static::PROP_OUTDOOR_TEMP] = 'Уличная температура';
        }

        return $properties;
    }

    public function updatePageElements(int $pageId): void
    {
        $paramsFlag = $this->boilersParamsFlag;
        $page = Page::find($pageId);
        $sort = 1;

        if ($paramsFlag->ch_current_temp) {
            Elements::updateOrCreate(
                [
                    'id_object' => $this->id_object,
                    'handle' => 'ch_current_temp',
                ],
                [
                    'name' => 'ЦО', 'type' => 'label',
                    'page' => $pageId, 'parent' => 0,
                    'sort' => $sort,
                    'position' => 2, 'active' => 1,
                    'settings' => 0, 'units' => '℃',
                ]
            );

            $sort++;
        } else {
            $page->elements()->where('handle', 'ch_current_temp')->delete();
        }

        if ($paramsFlag->ch_setpoint_temp) {
            $element = Elements::updateOrCreate(
                [
                    'id_object' => $this->id_object,
                    'handle' => 'ch_setpoint_temp',
                ],
                [
                    'name' => 'Уставка ЦО', 'type' => 'label',
                    'page' => $pageId, 'parent' => 0,
                    'sort' => $sort,
                    'position' => 2, 'active' => 1,
                    'settings' => 1, 'units' => '℃',
                ]
            );

            $element->internalPages()->create();

            $sort++;
        } else {
            $page->elements()->where('handle', 'ch_setpoint_temp')->delete();
        }

        if ($paramsFlag->return_temp) {
            Elements::updateOrCreate(
                [
                    'id_object' => $this->id_object,
                    'handle' => 'return_temp',
                ],
                [
                    'name' => 'Обратка', 'type' => 'label',
                    'page' => $pageId, 'parent' => 0,
                    'sort' => $sort,
                    'position' => 2, 'active' => 1,
                    'settings' => 0, 'units' => '℃',
                ]
            );

            $sort++;
        } else {
            $page->elements()->where('handle', 'return_temp')->delete();
        }

        if ($paramsFlag->dhw_current_temp) {
            Elements::updateOrCreate(
                [
                    'id_object' => $this->id_object,
                    'handle' => 'dhw_current_temp',
                ],
                [
                    'name' => 'ГВС', 'type' => 'label',
                    'page' => $pageId, 'parent' => 0,
                    'sort' => $sort,
                    'position' => 2, 'active' => 1,
                    'settings' => 0, 'units' => '℃',
                ]
            );

            $sort++;
        } else {
            $page->elements()->where('handle', 'dhw_current_temp')->delete();
        }

        if ($paramsFlag->dhw_setpoint_temp) {
            $element = Elements::updateOrCreate(
                [
                    'id_object' => $this->id_object,
                    'handle' => 'dhw_setpoint_temp',
                ],
                [
                    'name' => 'Уставка ГВС', 'type' => 'label',
                    'page' => $pageId, 'parent' => 0,
                    'sort' => $sort,
                    'position' => 2, 'active' => 1,
                    'settings' => 1, 'units' => '℃',
                ]
            );

            $element->internalPages()->create();

            $sort++;
        } else {
            $page->elements()->where('handle', 'dhw_setpoint_temp')->delete();
        }

        if ($paramsFlag->outdoor_temp) {
            Elements::updateOrCreate(
                [
                    'id_object' => $this->id_object,
                    'handle' => 'outdoor_temp',
                ],
                [
                    'name' => 'Уличная температура', 'type' => 'label',
                    'page' => $pageId, 'parent' => 0,
                    'sort' => $sort,
                    'position' => 2, 'active' => 1,
                    'settings' => 0, 'units' => '℃',
                ]
            );

            $sort++;

            $status = null;

            switch ($this->heating_mode) {
                case static::HEATING_MODE_MANUAL:
                    $status = 'off';
                    break;
                case static::HEATING_MODE_WC:
                    $status = 'on';
                    break;
            }

            Elements::updateOrCreate(
                [
                    'id_object' => $this->id_object,
                    'handle' => 'weather_compensation',
                ],
                [
                    'name' => 'ПЗА', 'type' => 'switch',
                    'page' => $pageId, 'parent' => 0,
                    'sort' => $sort, 'status' => $status,
                    'position' => 2, 'active' => 1,
                    'settings' => 0,
                ]
            );

            $sort++;
        } else {
            $page->elements()->where('handle', 'outdoor_temp')->delete();
            $page->elements()->where('handle', 'weather_compensation')->delete();
        }

        if ($paramsFlag->error_code) {
            Elements::updateOrCreate(
                [
                    'id_object' => $this->id_object,
                    'handle' => 'error_code',
                ],
                [
                    'name' => 'Ошибка', 'type' => 'label',
                    'page' => $pageId, 'parent' => 0,
                    'sort' => $sort,
                    'position' => 2, 'active' => 1,
                    'settings' => 0,
                ]
            );

            $sort++;
        } else {
            $page->elements()->where('handle', 'error_code')->delete();
        }
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

    public function boilersParam(): HasOne
    {
        return $this->hasOne(BoilersParam::class);
    }

    public function boilersParamsFlag(): HasOne
    {
        return $this->hasOne(BoilersParamsFlag::class);
    }
}
