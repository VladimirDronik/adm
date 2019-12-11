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
 * @property-read \App\Models\Method|null $emethod
 * @property-read \App\Models\HomeObject|null $eobject
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SchedulerPoint[] $not_system_points
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SchedulerPoint[] $points
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SchedulerPoint[] $system_points
 * @property int $is_system
 * @property int $is_hidden
 * @property-read \App\Models\Script|null $escript
 * @property-read mixed $has_method
 * @property-read mixed $has_script
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerTask whereIsHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerTask whereIsSystem($value)
 */
class SchedulerTask extends Model
{
    const SYSTEM = 1;
    const NOT_SYSTEM = 0;

    protected $table = 'scheduler_tasks';
    public $timestamps = false;
    protected $guarded = ['id'];

    /* attributes */

    public function getHasMethodAttribute()
    {
        return !is_null($this->method);
    }

    public function getHasScriptAttribute()
    {
        return !is_null($this->script);
    }

    /**
     * Если есть системный термостат или нет системного метода, то расписание редактировать можно.
     * Если есть системный счетчик, то нельзя.
     *
     * @return bool
     */
    public function getIsPointEditableAttribute()
    {
        if (!optional($this->emethod)->is_system) {
            return true;
        }

        return Termostat::whereHas('iobject', function ($query) {
            $query->where('is_system', 1)->where('id', $this->object);
        })->exists();
    }

    /* relations */

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function emethod()
    {
        return $this->belongsTo(Method::class, 'method', 'id');
    }

    public function escript()
    {
        return $this->belongsTo(Script::class, 'script', 'id');
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
