<?php

namespace App\Http\Controllers\Ajax;

use App\Models\View;
use App\Models\Method;
use Illuminate\Http\Request;
use App\Services\ObjectService;
use App\Http\Controllers\Controller;
use App\Repositories\ViewRepository;
use App\Repositories\ObjectRepository;

class ViewObjectController extends Controller
{
    public function __construct(
        private ObjectService $service
    ) {
    }

    /**
     * Загрузка объектов в модальное окно
     */
    public function getViewAll(Request $r, ObjectRepository $objectRep)
    {
        $objectArray = explode(',', $r->object);
        $objectName = $objectArray[0] != 'empty' ? $objectArray[1] : 'empty';
        $objects = $objectRep->getAll();
        $html = (string) view('ajax.view_objects', [
            'objects' => $objects,
            'view' => $objectArray[2],
        ]);

        return response()->json([
            'success' => true,
            'html' => $html,
            'object_name' => $objectName,
        ]);
    }

    /**
     * Привязка объекта к порту устройства
     */
    public function addObjectToView(Request $r, ViewRepository $viewRep)
    {
        $viewRep->updateObject($r->all());
    }

    public function getMethodAll(Request $r)
    {
        $methodArray = explode(',', $r->input('method'));
        $methodName = $methodArray[0] != 'empty' ? $methodArray[1] : 'empty';
        $view = View::find($r->input('id'));
        $methods = Method::where('id_object', $view->id_object)->orderBy('name')->get();
        $html = (string) view('ajax.view_methods', [
            'methods' => $methods,
            'view' => $methodArray[2],
        ]);

        return response()->json([
            'success' => true,
            'html' => $html,
            'method_name' => $methodName,
        ]);
    }

    public function addMethodToView(Request $r, ViewRepository $viewRep)
    {
        $viewRep->updateMethod($r->all());
    }

    public function getMethodOffAll(Request $r)
    {
        $methodArray = explode(',', $r->input('method'));
        $methodName = $methodArray[0] != 'empty' ? $methodArray[1] : 'empty';
        $view = View::find($r->input('id'));
        $methods = Method::where('id_object', $view->id_object)->orderBy('name')->get();
        $html = (string) view('ajax.view_off_methods', [
            'methods' => $methods,
            'view' => $methodArray[2],
        ]);

        return response()->json([
            'success' => true,
            'html' => $html,
            'method_name' => $methodName,
        ]);
    }

    public function addOffMethodToView(Request $r, ViewRepository $viewRep)
    {
        $viewRep->updateOffMethod($r->all());
    }
}
