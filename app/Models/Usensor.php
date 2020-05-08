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
 * @mixin \Eloquent
 */
class Usensor extends Model
{
    protected $table = 'usensors';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }
}
