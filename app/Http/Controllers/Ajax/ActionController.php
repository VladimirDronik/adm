<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 23.04.21
 * Time: 14:24
 */

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\ActionService;
use Illuminate\Http\Request;

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
        // abort_if(!ajaxHas($r, ['id_event']), 400);

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
