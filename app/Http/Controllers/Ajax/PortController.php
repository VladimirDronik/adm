<?php

namespace App\Http\Controllers\Ajax;

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
}
