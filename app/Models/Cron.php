<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cron
 *
 * @property int $id
 * @property string $name
 * @property int $period
 * @property int|null $object
 * @property int|null $method
 * @property int $system
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cron newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cron newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cron query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cron whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cron whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cron whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cron whereObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cron wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cron whereSystem($value)
 * @mixin \Eloquent
 */
class Cron extends Model
{
    const PERIODS = [1, 5, 10, 15, 30, 60]; // minutes
    protected $table = 'cron';
    public $timestamps = false;

    public static function isInPeriods(int $period)
    {
        return in_array($period, self::PERIODS);
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
}
