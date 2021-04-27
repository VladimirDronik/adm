<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 23.04.21
 * Time: 14:24
 */

namespace App\Http\Controllers\Ajax;

use App\Services\ActionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActionController extends Controller
{

    private $service;

    public function __construct(ActionService $service)
    {
        $this->service = $service;
    }

    /**
     * Получение действий для указанного метода
     */
    public function getForEvent(Request $r)
    {
        abort_if(!ajaxHas($r, ['id_event']), 400);


        return response()->json(['actions' => $this->service->getForEvent((int)$r->id_event)]);
    }


    public function addAction(Request $r)
    {
        abort_if(!ajaxHas($r, ['id_event']), 400);

        $result = $this->service->addAction($r->id_event, $r);

        return response()->json(['result' =>  $result]);
    }


    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id_action']), 400);

        $result = $this->service->delete($r->id_action, $r);

        return response()->json(['result' =>  $result]);
    }
}