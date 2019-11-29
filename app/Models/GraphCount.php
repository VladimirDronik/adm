<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GraphCount
 *
 * @property int $id
 * @property string $date
 * @property int $id_count
 * @property int $value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphCount query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphCount whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphCount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphCount whereIdCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GraphCount whereValue($value)
 * @mixin \Eloquent
 */
class GraphCount extends Model
{
    protected $table = 'graph_counts';
    public $timestamps = false;
}
