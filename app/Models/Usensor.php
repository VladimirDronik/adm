<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usensor extends Model
{
    protected $table = 'usensors';
    public $timestamps = false;
    protected $guarded = ['id'];


    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }
}
