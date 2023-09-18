<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 03.12.20
 * Time: 13:23
 */

namespace App\Http\Controllers\Ajax;

use App\Services\VirtualService;
use Illuminate\Http\Request;

class VirtualsController
{
    public function __construct(
        private VirtualService $service
    ) {
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function delete(Request $r)
    {
        abort_if(! ajaxHas($r, ['id']), 400);

        return response()->json(['result' => $this->service->delete((int) $r->id)]);
    }
}
