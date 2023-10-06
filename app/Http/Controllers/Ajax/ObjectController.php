<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\ObjectRepository;
use App\Repositories\PortRepository;
use App\Services\ObjectService;
use Illuminate\Http\Request;

class ObjectController extends Controller
{
    public function __construct(
        private ObjectService $service
    ) {
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool) $this->service->delete((int) $r->id)]);
    }

    public function deleteAll(Request $r)
    {
        return response()->json(['result' => (bool) $this->service->deleteObjects($r->ids)]);
    }

    public function methods(Request $r)
    {
        abort_if(! ajaxHas($r, ['object_id']), 400);

        $methods = $this->service->getMethodsByObjectId((int) $r->object_id);

        return response()->json(['result' => true, 'methods' => $methods]);
    }

    public function properties(Request $r)
    {
        abort_if(! ajaxHas($r, ['object_id']), 400);

        $properties = $this->service->getPropertiesByObjectId((int) $r->object_id, true);

        return response()->json(['result' => true, 'properties' => $properties]);
    }

    public function methodsAndHandles(Request $r)
    {
        abort_if(! ajaxHas($r, ['object_id']), 400);

        $methods = $this->service->getMethodsByObjectId((int) $r->object_id);
        $handles = $this->service->getPropertiesByObjectId((int) $r->object_id);

        return response()->json(['result' => true, 'methods' => $methods, 'handles' => $handles]);
    }

    public function getObjects(Request $r)
    {
        abort_if(! ajaxHas($r, ['type_object']), 400);

        $objects = $this->service->getObjectsByType($r->type_object);

        return response()->json(['result' => true, 'objects' => $objects]);
    }

    public function store(Request $r)
    {
        abort_if(! ajaxHas($r, ['type', 'name']), 400);

        if (empty($r->name) || empty($r->type)) {
            return response()->json(['result' => false,
                'message' => 'Не указаны данные для создания объекта']);
        }

        if ($this->service->isNameExists($r->name)) {
            return response()->json(['result' => false,
                'message' => 'Объект с таким названием уже существует. Укажите другое название']);
        }

        $id = $this->service->store($r->except('_token'));
        $objects = $this->service->getObjectsArray();

        return response()->json(['result' => true, 'objects' => $objects, 'id' => $id]);
    }

    /**
     * Загрузка объектов в модальное окно
     */
    public function getViewAll(Request $r, ObjectRepository $object_rep)
    {
        $object_array = explode(',', $r->object);
        $object_name = $object_array[0] != 'empty' ? $object_array[1] : 'empty';
        $objects = $object_rep->getAll();
        $html = (string) view('ajax.objects', ['objects' => $objects, 'port' => $object_array[2]]);

        return response()->json(['success' => true] + compact('html', 'object_name'));
    }

    /**
     * Привязка объекта к порту устройства
     *
     * @param  int  $id_port "id порта, к которому добавляем объект"
     * @return void
     */
    public function addObjectToPort(Request $r, PortRepository $port_rep)
    {
        $port_rep->updateObject($r->all());
    }
}
