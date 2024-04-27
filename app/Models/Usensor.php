<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Usensor
 *
 * @property int $id
 * @property int|null $id_object id датчика из таблицы объектов
 * @property string $name название датчика
 * @property float|null $temp текущая температура
 * @property float|null $hum текущая влажность
 * @property float|null $lux текущий уровень освещенности
 * @property int $device_id id устройства (контроллера), на котором висит датчик
 * @property int $port_SCL порт SCL контроллера на котором висит датчик
 * @property int $port_SDA порт SDA контроллера на котором висит датчик
 * @property int|null $room
 * @property-read \App\Models\HomeObject $eobject
 * @property-read \App\Models\HomeObject|null $iobject
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor whereHum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor whereLux($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor wherePortSCL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor wherePortSDA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usensor whereTemp($value)
 *
 * @mixin \Eloquent
 */
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
}
