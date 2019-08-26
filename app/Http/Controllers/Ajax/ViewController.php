<?php

namespace App\Http\Controllers\Ajax;

use App\Services\ViewService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewController extends Controller
{
    private $service;

    public function __construct(ViewService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!$r->ajax() || !$r->has('id'), 400);

        return response()->json(['result' => (bool)$this->service->delete($r->id)]);
    }

    public function active(Request $r)
    {
        abort_if(!$r->ajax(), 400);

    }
}


