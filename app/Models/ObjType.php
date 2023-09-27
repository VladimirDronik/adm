<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ObjType
 *
 * @property int $id
 * @property string $name
 * @property string|null $label
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereName($value)
 * @mixin \Eloquent
 */
class ObjType extends Model
{
    const TYPE_COUNT = 'count';
    const TYPE_TERMOSTAT = 'termostat';
    const TYPE_HYGROSTAT = 'hygrostat';
    const TYPE_LIGHTSTAT = 'lightstat';
    const TYPE_USENSOR = 'usensor';
    const TYPE_DIMMER = 'dimmer';
    const TYPE_BUTTON = 'button';
    const TYPE_SWITCH = 'switch';
    const TYPE_RELAY = 'relay';
    const TYPE_SOCKET = 'socket';
    const TYPE_DRYCONTACT = 'drycontact';
    const TYPE_MOTIONSENSOR = 'motionsensor';
    const TYPE_CARBMONOXIDE = 'carbsens';
    const TYPE_VIRTUAL = 'virtual';
    const TYPE_MANOMETR = 'manometr';
    const TYPE_BOILER = 'boiler';
    const TYPE_BOILER_GVS = 'boiler_gvs';
    const TYPE_CONDITIONER = 'conditioner';
    const TYPE_YANDEX_STATION = 'yandex_station';
    const TYPE_TAPE = 'tape';

    protected $table = 'objtypes';
    public $timestamps = false;
}
