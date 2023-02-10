<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\ConditionerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConditionerController extends Controller
{
    private $conditionersRep;

    public function __construct(ConditionerRepository $conditionersRep)
    {
        $this->conditionersRep = $conditionersRep;
    }

    public function modelsByVendor(Request $r)
    {
        abort_if(!ajaxHas($r, ['vendor_id']), 400);

        $models = $this->conditionersRep->getModelsByVendor((int)$r->vendor_id);

        abort_if(!$models, 404);

        $arrayModels = [];

        foreach ($models as $model) {
            array_push($arrayModels, ['id' => $model->id, 'name' => $model->name]);
        }

        return response()->json(['result' => true, 'models' => $arrayModels]);
    }
}
