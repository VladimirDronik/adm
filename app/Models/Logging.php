<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Logging
 *
 * @property int $id
 * @property string $point
 * @property int $value
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Logging whereValue($value)
 * @mixin \Eloquent
 */
class Logging extends Model
{
    protected $table = 'logging';
    public $timestamps = false;


}
