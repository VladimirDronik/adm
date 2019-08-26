<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Script
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name Название скрипта
 * @property string $link ссылка на скрипт в папке скрипты
 * @property int|null $count количество раз, которое выполнился скрипт
 * @property int $system
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Script whereSystem($value)
 */
class Script extends Model
{
    public $timestamps = false;
}
