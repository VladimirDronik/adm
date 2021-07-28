<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\YandexStation
 *
 * @property int $id
 * @property string|null $speaker_id ID колонки
 * @property string|null $name название колонки
 * @property int|null $volume громкость по умолчанию
 * @property \App\Models\Room|null $room
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|YandexStation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|YandexStation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|YandexStation query()
 * @method static \Illuminate\Database\Eloquent\Builder|YandexStation whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexStation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexStation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexStation whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexStation whereSpeakerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|YandexStation whereVolume($value)
 * @mixin \Eloquent
 */
class YandexStation extends Model
{
    protected $table = 'yandexstations';
    public $timestamps = false;
    protected $guarded = ['id'];

    /* relations */

    public function iroom()
    {
        return $this->belongsTo(Room::class, 'room', 'id');
    }
}
