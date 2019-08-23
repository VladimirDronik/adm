<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $table = 'view_items';


    /**
     * Вывод отображений с фильтром по номеру помещения
     *
     * @param $idRoom id помещения для вывода отображений в этом помещении
     */
    public static function getViews($idRoom)
    {
        return View::where('room','=',$idRoom)->orderBy('sort')->get();
    }
}
