<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GraphLight
 *
 * @property int $id
 * @property int $id_count id датчика освещенности
 * @property string $datetime дата и время значения
 * @property float $value значение параметра
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphLight newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphLight newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphLight query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphLight whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphLight whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphLight whereIdCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphLight whereValue($value)
 *
 * @mixin \Eloquent
 */
class GraphLight extends Model
{
    protected $table = 'graph_lights';

    public $timestamps = false;
}
