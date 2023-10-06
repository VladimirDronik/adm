<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Room
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $style
 * @property int $sort
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereStyle($value)
 * @property int $lighting
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Termostat[] $termostats
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereLighting($value)
 * @property-read mixed $color_style
 * @property-read \App\Models\Temperature $temperature
 * @property int|null $group_room
 * @property int $is_group
 * @property-read mixed $is_separate_room
 * @property-read mixed $prefix_name
 * @property-read \App\Models\Room|null $roomGroup
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Room[] $rooms
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room group()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room room()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereGroupRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereIsGroup($value)
 * @property-read int|null $rooms_count
 * @property-read int|null $termostats_count
 */
class Room extends Model
{
    const COMMON_NAME = 'Общие';
    public $timestamps = false;

    /* attributes */

    public function getColorStyleAttribute()
    {
        $color = Color::where('name', $this->style)->first();

        if ($color) {
            return $color->value;
        }

        return '';
    }

    public function getPrefixNameAttribute()
    {
        return ($this->is_group ? 'Группа' : 'Помещение') .' «'.$this->name.'»';
    }

    public function getIsSeparateRoomAttribute()
    {
        return !$this->is_group && is_null($this->group_room);
    }

    /* scopes */

    public function scopeGroup($query)
    {
        $query->where('is_group', 1);
    }

    public function scopeRoom($query)
    {
        $query->where('is_group', 0);
    }

    /* relations */

    public function termostats()
    {
        return $this->hasMany(Termostat::class, 'room', 'id')->orderBy('id');
    }

    public function hygrostats()
    {
        return $this->hasMany(Hygrostat::class, 'room', 'id')->orderBy('id');
    }

    public function lightstats()
    {
        return $this->hasMany(Lightstat::class, 'room', 'id')->orderBy('id');
    }

    public function temperature()
    {
        return $this->hasOne(Temperature::class, 'id_room');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'group_room')->group();
    }

    public function roomGroup()
    {
        return $this->belongsTo(Room::class, 'group_room');
    }

    public function conditioners()
    {
        return $this->hasMany(Conditioner::class, 'id_room', 'id');
    }
}
