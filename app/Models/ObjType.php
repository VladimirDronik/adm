<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ObjType
 *
 * @property int $id
 * @property string $name
 * @property string $view_type
 * @property int $usestatus используется ли смена состояния у объекта
 * @property int $virt виртуальный объект или реальный
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereUsestatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereViewType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ObjType whereVirt($value)
 * @mixin \Eloquent
 */
class ObjType extends Model
{
    protected $table = 'objtypes';
    public $timestamps = false;
}
