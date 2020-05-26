<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motionsensor extends Model
{
    protected $table = 'motionsensors';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
