<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property string $type
 * @property string $message
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereType($value)
 * @mixin \Eloquent
 */
class Log extends Model
{
    public $timestamps = false;
    protected $dates = ['date'];
}
