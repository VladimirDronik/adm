<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(
        private PageService $service
    ) {
    }

    public function updateName(Request $r)
    {
        abort_if(! ajaxHas($r, ['id', 'name']), 400);

        $this->service->updateName((int) $r->id, (string) $r->name);

        return response()->json(['success' => true, 'html' => $r->name]);
    }

    public function updateLink(Request $r)
    {
        abort_if(! ajaxHas($r, ['id', 'link']), 400);

        $this->service->updateLink((int) $r->id, (string) $r->link);

        return response()->json(['success' => true, 'html' => $r->link]);
    }

    public function store(Request $r)
    {
        abort_if(! ajaxHas($r, ['name', 'type', 'link']), 400);

        return response()->json(['result' => (bool) $this->service->store($r->all())]);
    }

    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json(['result' => $this->service->delete((int) $r->id)]);
    }
}
