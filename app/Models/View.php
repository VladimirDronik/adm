<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\View
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View query()
 * @mixin \Eloquent
 */
class View extends Model
{
    protected $table = 'view_items';
    public $timestamps = false;
}
