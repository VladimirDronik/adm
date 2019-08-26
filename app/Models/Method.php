<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Method
 *
 * @property int $id
 * @property int $id_object
 * @property string $name Название метода объекта
 * @property int|null $script id скрипта из таблицы скриптов
 * @property string $comment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereScript($value)
 * @mixin \Eloquent
 */
class Method extends Model
{
    public $timestamps = false;
}
