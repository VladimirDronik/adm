<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $table = 'view_items';
<<<<<<< HEAD


    /**
     * Вывод отображений с фильтром по номеру помещения
     *
     * @param $idRoom id помещения для вывода отображений в этом помещении
     */
    public static function getViews($idRoom)
    {
        return View::where('room','=',$idRoom)->orderBy('sort')->get();
    }
=======
>>>>>>> 4d7144ce68cdd8e0e79af2ccb200d1d1a846fd67
}
