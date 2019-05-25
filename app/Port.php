<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    public $timestamps = false;


    /**
     * Добавление объекта к порту
     *
     * @param int id_port "id порта, к которому добавляем объект"
     * @param int id_object "id объекта, который добавляем к порту"
     *
     * @return void
     */
    static public function add_object($id_port, $id_object){

    if($id_object=='')
        $id_object = null;

    Port::where('id', $id_port)->update(['object' => $id_object]);


    }



}
