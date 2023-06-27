<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    protected $guarded = ['id'];

    public function relationRoom()
    {
        return $this->belongsTo(Room::class, 'room', 'id');
    }
}
