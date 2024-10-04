<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\SchedulerService;
use App\Http\Controllers\Controller;

class SchedulerController extends Controller
{
    public function __construct(
        private SchedulerService $service
    ) {
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json([
            'result' => (bool) $this->service->delete((int) $r->id),
        ]);
    }

    public function validateName(Request $r)
    {
        abort_if(! ajaxHas($r, ['id', 'name']), 400);

        return response()->json(
            $this->service->validateName((int) $r->id, $r->name)
        );
    }

    public function system(Request $r)
    {
        abort_if(! ajaxHas($r, ['id', 'is_system']), 400);

        return response()->json([
            'result' => $this->service->changeSystem(
                (int) $r->id, (int) $r->is_system
            ),
        ]);
    }

    public function hidden(Request $r)
    {
        abort_if(! ajaxHas($r, ['id', 'is_hidden']), 400);

        return response()->json([
            'result' => $this->service->changeHidden(
                (int) $r->id, (int) $r->is_hidden
            ),
        ]);
    }

    public function active(Request $r)
    {
        abort_if(! ajaxHas($r, ['id', 'active']), 400);

        return response()->json([
            'result' => $this->service->changeActive(
                (int) $r->id, (int) $r->active
            ),
        ]);
    }
}
