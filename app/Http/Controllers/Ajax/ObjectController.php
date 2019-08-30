<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\ObjectRepository;
use App\Repositories\PortRepository;
use App\Services\ObjectService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ObjectController extends Controller
{
    private $service;

    public function __construct(ObjectService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool)$this->service->delete((int)$r->id)]);
    }

    public function methods(Request $r)
    {
        abort_if(!ajaxHas($r, ['object_id']), 400);

        $methods = $this->service->getMethodsByObjectId((int)$r->object_id);

        return response()->json(['result' => true, 'methods' => $methods]);
    }

    /**
     * Загрузка объектов в модальное окно
     */
    public function getViewAll(Request $r, ObjectRepository $object_rep)
    {
        $object_array = explode(',',$r->object);
        $object_name = $object_array[0] != 'empty' ? $object_array[1] : 'empty';
        $objects = $object_rep->getAll();
        $html = (String) view('ajax.objects', ['objects' => $objects, 'port' => $object_array[2]]);

        return response()->json(['success' => true] + compact('html', 'object_name'));
    }

    /**
     * Привязка объекта к порту устройства
     *
     * @param int $id_port "id порта, к которому добавляем объект"
     *
     * @return void
     */
    public function addObjectToPort(Request $r, PortRepository $port_rep)
    {
        $port_rep->updateObject($r->all());
    }
}
