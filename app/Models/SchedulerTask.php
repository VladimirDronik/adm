<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SchedulerTask
 *
 * @property int $id
 * @property string $name Название задачи
 * @property int|null $object id объекта
 * @property int|null $method id метода объекта
 * @property int|null $script Если не null, то сначала выполняется этот скрипт
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerTask whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerTask whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerTask whereObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerTask whereScript($value)
 * @mixin \Eloquent
 */
class SchedulerTask extends Model
{
    const SYSTEM = 1;
    const NOT_SYSTEM = 0;

    protected $table = 'scheduler_tasks';
    public $timestamps = false;
    protected $guarded = ['id'];

    /* relations */

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function emethod()
    {
        return $this->belongsTo(Method::class, 'method', 'id');
    }

    public function points()
    {
        return $this->hasMany(SchedulerPoint::class, 'id_task', 'id')->orderBy('type');
    }

    public function system_points()
    {
        return $this->hasMany(SchedulerPoint::class, 'id_task', 'id')
            ->where('system', self::SYSTEM)->orderBy('type');
    }

    public function not_system_points()
    {
        return $this->hasMany(SchedulerPoint::class, 'id_task', 'id')
            ->where('system', self::NOT_SYSTEM)->orderBy('type');
    }
}
