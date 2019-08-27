<?php

namespace App\Http\Controllers\Ajax;

use App\Models\HomeObject;
use App\Models\Port;
use App\Services\ObjectService;
use App\Services\SceneService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SceneController extends Controller
{
    private $service;

    public function __construct(SceneService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!$r->ajax() || !$r->has('id'), 400);

        return response()->json(['result' => (bool)$this->service->delete((int)$r->id)]);
    }

    // todo refactoring below

    /**
     * Загрузка объектов в модальное окно
     */
    public function load_to_port()
    {
        $object_array = explode(',',$_POST['object']);

        if ($object_array[0]!='empty')
            $object_name = $object_array[1];
        else
            $object_name = 'empty';

        //Получение объектов из таблицы
        $objects = HomeObject::all();

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

       $res = Port::add_object($_POST['id_port'], $_POST['id_object']);
       //return response()->json(array('success' => true, 'status'=>$res));
    }






}
