<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Graph
 *
 * @property int $id
 * @property int $id_termostat id термостата из таблицы термостатов
 * @property string $datetime дата и время значения
 * @property float $value значение параметра
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Graph newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Graph newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Graph query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Graph whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Graph whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Graph whereIdTermostat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Graph whereValue($value)
 * @mixin \Eloquent
 */
class Graph extends Model
{
    protected $table = 'graph';
    public $timestamps = false;

    /* relations */

    public function etermostat()
    {
        return $this->belongsTo(Termostat::class, 'id_termostat', 'id');
    }
}
