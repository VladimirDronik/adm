<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = false;


    public static function getIdByIdObject(int $idObject) {

        return Notification::select('id')->where('id_object', $idObject)->first();
    }
}
