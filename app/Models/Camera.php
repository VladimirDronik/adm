<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    protected $guarded = ['id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
