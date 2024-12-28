<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorsParam extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getUnits(): array
    {
        return [
            'celsius' => '°C',
            'percent' => '%',
            'ppm' => 'ppm',
            'atm' => 'атм',
            'pascal' => 'Па',
            'bar' => 'бар',
            'mmhg' => 'мм рт.ст.',
            'lux' => 'люкс',
            'ampere' => 'А',
            'kilowatt_hour' => 'кВт/ч',
            'cubic_meter' => 'м³',
            'gigacalorie' => 'Гкал',
            'mcg_m3'  => 'мкг/м³',
            'watt' => 'Вт',
            'kelvin' => 'К',
            'volt' => 'В',
            null => '',
        ];
    }

    public static function getParams(): array
    {
        return [
            'battery_level' => 'Уровень заряда батареи',
            'co2_level' => 'Концентрация углекислого газа',
            'humidity' => 'Влажность',
            'illumination' => 'Освещенность',
            'pm1_density' => 'Уровень загрязнения воздуха частицами PM1',
            'pm2.5_density' => 'Уровень загрязнения воздуха частицами PM2.5',
            'pm10_density' => 'Уровень загрязнения воздуха частицами PM10',
            'pressure' => 'Давление',
            'temperature' => 'Температура',
            'tvoc' => 'Уровень загрязнения воздуха органическими веществами',
            'water_level' => 'Уровень жидкости',
        ];
    }

    public function getUnitNameAttribute()
    {
        return self::getUnits()[$this->units] ?? $this->units;
    }

    public function getParamNameAttribute()
    {
        return self::getParams()[$this->param] ?? $this->param;
    }

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'object_id', 'id');
    }
}
