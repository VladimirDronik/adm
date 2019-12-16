<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dimmer extends Model
{
    protected $table = 'dimmers';
    public $timestamps = false;

    /* relations */

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
