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
 * @property-read \App\Models\SchedulerTask $etask
 * @property-read mixed $description
 * @property-read mixed $is_close
 * @property-read mixed $is_system
 * @property-read mixed $rus_type
 * @property-read mixed $single_rus_type
 * @property-read mixed $is_system_method
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
    protected $casts = ['close' => 'integer', 'system' => 'integer'];

    public static function isInCronPeriods(int $period)
    {
        return in_array($period, self::CRON_PERIODS);
    }

    public function getIsCloseAttribute()
    {
        return $this->close == 1;
    }

    public function getIsSystemAttribute()
    {
        return $this->system == 1;
    }

    public function getCronDescription()
    {
        return 'Каждые '.$this->time.' мин';
    }

    public function getDayDescription()
    {
        return 'В '.$this->time.' по '.daysToShortRus($this->days).' каждую неделю';
    }

    public function getMonthDescription()
    {
        $days = str_replace(",",", ", $this->days);
        return 'В '.$this->time.' по '.$days.' числам каждого месяца';
    }

    public function getYearDescription()
    {
        $dates = str_replace(",",", ", $this->days);
        return 'В '.$this->time.' по датам '.$dates.' каждого года';
    }

    private function getSystemHtml()
    {
        if ($this->is_system) {
            return '&nbsp;&nbsp;<i class="fa fa-exclamation-triangle" title="Системный"></i>';
        }

        return '';
    }

    public function getDescriptionAttribute()
    {
        $description = '';
        switch ($this->type) {
            case self::TYPE_CRON:
                $description = $this->getCronDescription();
                break;
            case self::TYPE_DAYS:
                $description = $this->getDayDescription();
                break;
            case self::TYPE_MONTHS:
                $description = $this->getMonthDescription();
                break;
            case self::TYPE_YEARS:
                $description = $this->getYearDescription();
                break;
        }

        if ($description !== '') {
            return $description . $this->getSystemHtml();
        }

        return '';
    }

    public function getIsSystemMethodAttribute()
    {
        return (bool)$this->etask->emethod->is_system;
    }

    /* relations */

    public function etask()
    {
        return $this->belongsTo(SchedulerTask::class, 'id_task', 'id');
    }
}
