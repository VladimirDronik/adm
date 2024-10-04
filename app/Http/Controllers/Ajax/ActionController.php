<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\ActionService;
use App\Http\Controllers\Controller;

class ActionController extends Controller
{
    public function __construct(
        private ActionService $service
    ) {
    }

    /**
     * Получение действий для указанного метода
     */
    public function getForEvent(Request $r)
    {
        return response()->json(['actions' => $this->service->getForEvent($r->id_event, $r->actions)]);
    }

    public function addAction(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_event']), 400);

        $result = $this->service->addAction($r->id_event, $r->data);

        return response()->json(['result' => $result]);
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_action']), 400);

        $result = $this->service->delete($r->id_action, $r);

        return response()->json(['result' => $result]);
    }
}
