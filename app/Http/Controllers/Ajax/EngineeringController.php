<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 13.04.21
 * Time: 15:35
 */

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\EngineeringService;
use Illuminate\Http\Request;

class EngineeringController extends Controller
{

    private $service;

    public function __construct(EngineeringService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => $this->service->delete((int)$r->id, (bool)$r->del_checkbox)]);
    }

}