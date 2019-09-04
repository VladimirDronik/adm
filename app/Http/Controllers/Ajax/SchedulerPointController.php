<?php

namespace App\Http\Controllers\Ajax;

use App\Services\EventService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchedulerPointController extends Controller
{
    private $service;

    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => (bool)$this->service->deletePoint((int)$r->id)]);
    }

    public function store(Request $r)
    {
        abort_if(!ajaxHas($r, ['object_id', 'id', 'name', 'script_id', 'comment']), 400);

        return response()->json(['result' => true] + $this->service->storePoint($r->all()));
    }
}
