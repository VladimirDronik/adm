<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Virtual extends Model
{
    protected $table = 'virtualsdev';
    public $timestamps = false;


    /* relations */

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
