<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\EventService;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function __construct(
        private EventService $service
    ) {
    }

    public function update(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_event']), 400);

        $result = $this->service->update($r->id_event, $r);

        return response()->json(['result' => $result]);
    }

    public function create(Request $r)
    {
        $data = $this->service->create($r);

        if ($data) {
            $result = true;
        }

        return response()->json([
            'result' => $result,
            'data' => $data,
        ]);
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id_event']), 400);

        $result = $this->service->delete($r->id_event);

        return response()->json(['result' => $result]);
    }
}
