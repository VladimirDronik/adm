<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\MessageService;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function __construct(
        private MessageService $service
    ) {
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['message']), 400);

        return response()->json([
            'result' => (bool) $this->service->delete(
                (string) $r->message,
                (int) $r->id_object
            ),
        ]);
    }

    public function store(Request $r)
    {
        abort_if(! ajaxHas($r, ['data']), 400);

        return response()->json([
            'result' => true,
            'data' => $this->service->store($r->data),
        ]);
    }
}
