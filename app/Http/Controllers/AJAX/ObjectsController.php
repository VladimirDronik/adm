<?php

namespace App\Http\Controllers\AJAX;

use App\Object AS object;
use App\Port AS port;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ObjectsController extends Controller
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
        $objects = object::all();


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

    /**
     * Загрузка метода в модальное окно
     **/
    public function load_method()
    {
        $method = $_POST['methodmode'];

        // Если выбираемый метод равен уже существующему методу порта
        if($_POST['cur_method'] == $method)
            $value = $_POST['value'];
        else
            $value = 'отсутствует';



        switch ($method) {

            case 'easy':

                // Разбираем значение для простого действия
                if ($value != 'отсутствует'){

                    $easy = explode(';', $_POST['value']);
                    $easy1 = explode(':', $easy[1]);

                    $device = $easy[0];
                    $port = $easy1[0];
                    $act = $easy1[1];

                }
                else{

                    $device = 'отсутствует';
                    $port = 'отсутствует';
                    $act = 'отсутствует';

                }


                $string = '<button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Устройство: '.$device.'</button>&nbsp;
                    <button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Порт: '.$port.'</button>&nbsp;
                    <button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Действие: '.$act.'</button>
                    <br><br><div class="alert alert-info">В этом режиме при срабатывании входного порта будет выполняться 
                    действие с другим портом этого же или другого устройства. Для этого необхоидмо добавить команду 
                    в формате "Устройство; Порт: Действие"</div>';
                break;


            case 'method':


                $string = '<button type="button" class="btn btn-warning  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Объект: '.$value.'</button>&nbsp;
                    <button type="button" class="btn btn-warning  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Метод:</button>
                    <div class="alert alert-info">В этом режиме при срабатывании входного порта будет выполняться
                     метод выбранного здесь объекта</div>';
                break;

            case 'script':

                $string = '<button type="button" class="btn btn-info  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Скрипт: '.$value.'</button>';
                break;

            case 'none':
                $string = '<div class="alert alert-info">Действие при срабатывании порта не выбрано</div>';
                break;
        }



        return response()->json(array('success' => true, 'html'=>$string));
    }




}
