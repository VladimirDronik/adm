<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class carbmonoxide extends Model
{
    protected $table = 'carbmonoxide';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}

