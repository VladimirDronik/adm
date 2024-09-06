<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Usensor extends Model
{
    protected $table = 'usensors';

    public $timestamps = false;

    protected $guarded = ['id'];

    const TYPE_HTU21D = 'htu21d';

    const TYPE_BH1750 = 'bh1750';

    const TYPE_BME280 = 'bme280';

    const TYPE_SCD40 = 'scd40';

    const TYPE_SCD41 = 'scd41';

    const TYPE_OUTDOORV2 = 'outdoorv2';

    const TYPE_OUTDOORV3 = 'outdoorv3';

    const TYPE_PTSENSOR = 'ptsensor';

    const TYPE_MAX44009 = 'max44009';

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_HTU21D => 'HTU21D',
            self::TYPE_BH1750 => 'BH1750',
            self::TYPE_BME280 => 'BME280',
            self::TYPE_SCD40 => 'SCD40',
            self::TYPE_SCD41 => 'SCD41',
            self::TYPE_OUTDOORV2 => 'Outdoor v2',
            self::TYPE_OUTDOORV3 => 'Outdoor v3',
            self::TYPE_PTSENSOR => 'PTsensor',
            self::TYPE_MAX44009 => 'MAX44009',
        ];

        return $is_full ? $types : array_keys($types);
    }

    public function getTypeNameAttribute()
    {
        return static::getTypes(true)[$this->type] ?? '';
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function relatedRoom()
    {
        return $this->belongsTo(Room::class, 'room', 'id');
    }

    public function lightstats()
    {
        return $this->hasMany(Lightstat::class, 'usensor_id', 'id_object');
    }

    public function termostats()
    {
        return $this->hasMany(Termostat::class, 'usensor_id', 'id_object');
    }

    public function hygrostats()
    {
        return $this->hasMany(Hygrostat::class, 'usensor_id', 'id_object');
    }

    public function pressurestats()
    {
        return $this->hasMany(Pressurestat::class, 'usensor_id', 'id_object');
    }

    public function carbdioxides()
    {
        return $this->hasMany(Carbdioxide::class, 'usensor_id', 'id_object');
    }

    public function sensors(): Collection
    {
        $sensors = $this->lightstats->concat($this->termostats)
            ->concat($this->hygrostats)
            ->concat($this->pressurestats)
            ->concat($this->carbdioxides);

        return $sensors;
    }
}
