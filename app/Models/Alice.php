<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alice extends Model
{
    protected $table = 'alice_devices';

    public $timestamps = false;

    protected $guarded = ['id'];
}
