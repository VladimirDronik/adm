<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 27.04.21
 * Time: 15:32
 */

namespace App\Http\Controllers\Ajax;
use App\Services\EventService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{

    private $service;

    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    public function update(Request $r)
    {
        abort_if(!ajaxHas($r, ['id_event']), 400);

        $result = $this->service->update($r->id_event, $r);

        return response()->json(['result' =>  $result]);
    }


    public function create(Request $r)
    {
        $data = $this->service->create($r);

        if($data)
            $result = true;

        return response()->json(['result' =>  $result, 'data' => $data]);
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id_event']), 400);

        $result = $this->service->delete($r->id_event);

        return response()->json(['result' =>  $result]);
    }


}