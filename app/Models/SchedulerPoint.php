<?php

namespace App\Models;

use App\Traits\Models\SchedulerPointType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SchedulerPoint
 *
 * @property int $id
 * @property string $type w-недельные, m-месячные, y-годовые
 * @property string $time время выполнения скрипта
 * @property string $days дни выполнения скрипта
 * @property int $id_task id задачи расписания
 * @property int $close Если 1, то событие нельзя удалить
 * @property int $system Если 1, тоне показываем в событиях у клиента
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerPoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerPoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerPoint query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerPoint whereClose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerPoint whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerPoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerPoint whereIdTask($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerPoint whereSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerPoint whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SchedulerPoint whereType($value)
 * @mixin \Eloquent
 */
class SchedulerPoint extends Model
{
    use SchedulerPointType;

    const CRON_PERIODS = [1, 5, 10, 15, 30, 60]; // minutes

    const TYPE_DAYS = 'w';
    const TYPE_MONTHS = 'm';
    const TYPE_YEARS = 'y';
    const TYPE_CRON = 'c';

    protected $table = 'scheduler_points';
    public $timestamps = false;

    public static function isInCronPeriods(int $period)
    {
        return in_array($period, self::CRON_PERIODS);
    }

    /* relations */

    public function etask()
    {
        return $this->belongsTo(SchedulerTask::class, 'id_task', 'id');
    }
}
