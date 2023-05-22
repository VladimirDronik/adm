<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientInfo extends Model
{
    protected $table = 'client_info';
    protected $guarded = ['id'];

    public static function getInfo()
    {
        return self::first();
    }
}
