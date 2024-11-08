<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'object_id', 'id');
    }
}
