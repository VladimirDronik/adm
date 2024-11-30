<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'object_id', 'id');
    }

    public static function getTypes(): array
    {
        return [
            '' => 'Не выбрано',
            'custom' => 'custom',
            'ds18b20' => 'ds18b20',
            'htu21d' => 'htu21d',
            'outdoorv3' => 'outdoorv3',
            'bh1750' => 'bh1750',
            'max44009' => 'max44009',
            'bme280' => 'bme280',
            'scd40' => 'scd40',
            'scd41' => 'scd41',
            'ptsensor' => 'ptsensor',
        ];
    }

    public static function getSources(): array
    {
        return [
            '' => 'Не выбрано',
            'megad' => 'megad',
            'modbus' => 'modbus',
            'mqtt' => 'mqtt',
        ];
    }
}
