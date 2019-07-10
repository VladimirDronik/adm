<?php

namespace App\Http\Controllers\AJAX;

use App\HomeObject AS obj;
use App\Port AS port;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ObjectController extends Controller
{

    public function index()
    {



    }


    /**
     * Загрузка объектов в модельное окно
     *
     *
     */
    public function load_to_port()
    {

        $object_array = explode(',',$_POST['object']);

        if ($object_array[0]!='empty')
            $object_name = $object_array[1];
        else
            $object_name = 'empty';


        //Получение объектов из таблицы
        $objects = obj::all();


        $returnHTML = (String) view('AJAX.objects', ['objects' => $objects, 'port' => $object_array[2] ]);

        //$returnHTML = (String) view('AJAX.objects')->with('objects', $objects)->render();

        return response()->json(array('success' => true, 'html'=>$returnHTML, 'object_name' => $object_name));

    }



    /**
     * Привязка объекта к порту устройства
     *
     * @param int $id_port "id порта, к которому добавляем объект"
     *
     * @return void
     */
    public function add_to_port()
    {

       $res = port::add_object($_POST['id_port'], $_POST['id_object']);
       //return response()->json(array('success' => true, 'status'=>$res));
    }






}
