<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Pressurestat extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    const TYPE_BMX280 = 'bmx280';

    const TYPE_PTSENSOR = 'ptsensor';

    public static function getSensorTypes()
    {
        return [
            self::TYPE_BMX280 => 'Атмосферное давление',
            self::TYPE_PTSENSOR => 'Давление жидкости',
        ];
    }

    public static function getFullPressurestatIds()
    {
        return [
            0 => 'Реакция на уменьшение давления',
            1 => 'Реакция на увеличение давления',
        ];
    }

    public function relatedObject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function influenceObject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function getRusPressurestatAttribute()
    {
        return static::getFullPressurestatIds()[$this->mode] ?? '';
    }

    public function getRusSensorTypeAttribute()
    {
        return static::getSensorTypes()[$this->type_sensor] ?? '';
    }

    public function methodOn()
    {
        return $this->belongsTo(Method::class, 'method_on', 'id');
    }

    public function methodOff()
    {
        return $this->belongsTo(Method::class, 'method_off', 'id');
    }

    public function lastGraphs()
    {
        return $this->hasMany(GraphPressure::class, 'id_count', 'id')
            ->where('datetime', '>=', Carbon::now()->subDays(7))
            ->orderBy('datetime');
    }
}
