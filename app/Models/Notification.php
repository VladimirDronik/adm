<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = false;


    public static function getIdByIdObject(int $idObject) {

        $result = Notification::select('id')->where('id_object', $idObject)->first();

        if ($result)
        return $result->id;
        else return false;
    }
}
