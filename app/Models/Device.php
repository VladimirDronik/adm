<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Device
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $ip_address
 * @property string $description
 * @property int $type
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device whereType($value)
 * @property-read \App\Models\DevType $devtype
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Port[] $ports
 */
class Device extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];

    /* relations */

    public function devtype()
    {
        return $this->belongsTo(DevType::class, 'type', 'id');
    }

    public function ports()
    {
        return $this->hasMany(Port::class, 'id_device', 'id')->orderBy('num_port');
    }
}
