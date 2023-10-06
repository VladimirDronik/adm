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
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log whereType($value)
 *
 * @mixin \Eloquent
 *
 * @property-read mixed $rus_type
 */
class Log extends Model
{
    const NO_TYPE_NAME = 'без категории';

    public $timestamps = false;

    protected $dates = ['date'];

    public function getRusTypeAttribute()
    {
        if (empty(trim($this->type))) {
            return self::NO_TYPE_NAME;
        }

        return $this->type;
    }
}
