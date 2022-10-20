<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Method;
use App\Models\Port;
use App\Repositories\PortRepository;
use App\Services\PortService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PortController extends Controller
{
    private $service;

    public function __construct(PortService $service)
    {
        $this->service = $service;
    }

    public function updateComment(Request $r)
    {
        abort_if(!ajaxHas($r, ['device_id', 'port_id', 'comment']), 400);

        $this->service->updateComment($r->all());

        return response()->json(['success' => true, 'html' => trim($r->comment)]);
    }

    /**
     * Загрузка метода в модальное окно
     **/
    public function getViewMethod(Request $r)
    {
        $html = $this->service->getViewMethod($r);

        return response()->json(['success' => true, 'html' => $html]);
    }

    /**
     * Загрузка данных для выбора действия порта
     */
    public function getViewData(Request $r)
    {
        return response()->json(['success' => true] + $this->service->getViewData($r));
    }

    /**
     * Сохранение метода для порта
     */
    public function storeMethod(Request $r)
    {
        $this->service->storeMethod($r);
    }

    public function getMethodAll(Request $r)
    {
        $method_array = explode(',',$r->input('method'));
        $method_name = $method_array[0] != 'empty' ? $method_array[1] : 'empty';
        $port = Port::find($r->input('id'));
        $methods = Method::where('id_object',$port->object)->orderBy('name')->get();
        $html = (String) view('ajax.port_methods', ['methods' => $methods, 'view' => $method_array[2]]);

        return response()->json(['success' => true] + compact('html', 'method_name'));
    }

    public function addMethodToPort(Request $r, PortRepository $port_rep)
    {
        $port_rep->updateMethodByModal($r->all());
    }

    public function getPortMethods(Request $r)
    {
        try {
            $data = $this->service->getPortMethods($r->data);
            return response()->json(['result' => true] + $data);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            return response()->json(['result' => false]);
        }
    }

    public function deletePortMethod(Request $r)
    {
        try {
            $this->service->deletePortMethod($r->except('_token'));
            return response()->json(['result' => true]);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            return response()->json(['result' => false]);
        }
    }

    public function getObjectMethods(Request $r)
    {
        try {
            $methods = $this->service->getObjectMethods($r->object_id);
            return response()->json(['result' => true, 'methods' => $methods]);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            return response()->json(['result' => false]);
        }
    }

    public function updatePortMethod(Request $r)
    {
        try {
            $data = $this->service->updatePortMethod($r->except('_token'));
            return response()->json(['result' => true] + $data);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            return response()->json(['result' => false]);
        }
    }
}
