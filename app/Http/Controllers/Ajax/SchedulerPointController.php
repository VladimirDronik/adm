<?php

namespace App\Http\Controllers\Ajax;

use App\Services\SchedulerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchedulerPointController extends Controller
{
    private $service;

    public function __construct(SchedulerService $service)
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
        abort_if(!ajaxHas($r, ['data']), 400);

        try {
            $data = $this->service->storeOrUpdatePoint($r->data);
            return response()->json(['result' => true] + $data);
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении расписания элемента', [$r->all(), $e->getMessage()]);
            return response()->json(['result' => false]);
        }
    }
}
