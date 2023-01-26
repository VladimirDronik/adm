<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Conditioner
 *
 * @package App\Models
 * @mixin \Eloquent
 * @property-read mixed $rus_type
 */
class Conditioner extends Model
{

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
