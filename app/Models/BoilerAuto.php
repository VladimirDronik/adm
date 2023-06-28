<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class BoilerAuto extends Model
{
    public $timestamps = false;

    protected $table   = 'boiler_auto';
    protected $guarded = [];

    /* relations */

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object');
    }
}
