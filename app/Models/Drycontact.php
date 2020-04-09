<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drycontact extends Model
{
    protected $table = 'drycontacts';
    public $timestamps = false;

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
