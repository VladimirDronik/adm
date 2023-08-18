<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GraphHumidity
 *
 * @property int $id
 * @property int $id_hygrostat id датчика влажности
 * @property string $datetime дата и время значения
 * @property int $value значение параметра в процентах (от 0 до 100 вкл)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphHumidity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphHumidity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphHumidity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphHumidity whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphHumidity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphHumidity whereIdCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphHumidity whereValue($value)
 * @mixin \Eloquent
 */
class GraphHumidity extends Model
{
    protected $table = 'graph_hygrostats';
    public $timestamps = false;
}
