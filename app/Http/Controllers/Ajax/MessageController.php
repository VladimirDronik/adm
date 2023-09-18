<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['message']), 400);

        return response()->json(['result' => (bool) $this->service->delete((string) $r->message, (int) $r->id_object)]);
    }

    public function store(Request $r)
    {
        abort_if(! ajaxHas($r, ['data']), 400);

        return response()->json(['result' => true, 'data' => $this->service->store($r->data)]);
    }
}
