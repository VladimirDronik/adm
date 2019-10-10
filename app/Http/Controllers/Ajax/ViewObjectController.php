<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Method;
use App\Models\View;
use App\Repositories\ObjectRepository;
use App\Repositories\ViewRepository;
use App\Services\ObjectService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewObjectController extends Controller
{
    private $service;

    public function __construct(ObjectService $service)
    {
        $this->service = $service;
    }

    /**
     * Загрузка объектов в модальное окно
     */
    public function getViewAll(Request $r, ObjectRepository $object_rep)
    {
        $object_array = explode(',',$r->object);
        $object_name = $object_array[0] != 'empty' ? $object_array[1] : 'empty';
        $objects = $object_rep->getAll();
        $html = (String) view('ajax.view_objects', ['objects' => $objects, 'view' => $object_array[2]]);

        return response()->json(['success' => true] + compact('html', 'object_name'));
    }

    /**
     * Привязка объекта к порту устройства
     *
     * @param int $id_port "id порта, к которому добавляем объект"
     *
     * @return void
     */
    public function addObjectToView(Request $r, ViewRepository $view_rep)
    {
        $view_rep->updateObject($r->all());
    }

    public function getMethodAll(Request $r)
    {
        $method_array = explode(',',$r->input('method'));
        $method_name = $method_array[0] != 'empty' ? $method_array[1] : 'empty';
        $view = View::find($r->input('id'));
        $methods = Method::where('id_object',$view->id_object)->orderBy('name')->get();
        $html = (String) view('ajax.view_methods', ['methods' => $methods, 'view' => $method_array[2]]);

        return response()->json(['success' => true] + compact('html', 'method_name'));
    }

    public function addMethodToView(Request $r, ViewRepository $view_rep)
    {
        $view_rep->updateMethod($r->all());
    }
}
