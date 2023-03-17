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

    public function getCode(Request $r)
    {
        abort_if(!ajaxHas($r, ['kind', 'operationMode', 'fanMode', 'temp']), 400);

        $conditionerCode = $this->conditionersRep
            ->getCode((int)$r->kind, (string)$r->operationMode, (string)$r->fanMode, (float)$r->temp);

        return response()->json(['result' => true, 'code' => $conditionerCode ? $conditionerCode->code : '']);
    }

    public function readCode(Request $r)
    {
        abort_if(!ajaxHas($r, ['wbMir', 'ip']), 400);

        $readCodecommand = 'rs_control ir_scan -r -d wb-mir --ip ' . $r->ip . ' -u ' . $r->wbMir;
        $reciveCodecommand = 'rs_control ir_scan -g -d wb-mir --ip ' . $r->ip . ' -u ' . $r->wbMir;

        $readOutput = null;

        // chdir('D:/domains/touch-on');

        exec($readCodecommand, $readOutput);

        $readResponse = $readOutput ? json_decode($readOutput[0], true) : null;

        if ($readResponse && !$readResponse['error_code']) {
            $errorCode = 1;
            $reciveOutput = null;
            while ($errorCode == 1) {
                $reciveOutput = null;
                exec($reciveCodecommand, $reciveOutput);
                $reciveResponse = $reciveOutput ? json_decode($reciveOutput[0], true) : null;
                $errorCode = $reciveResponse['error_code'];
            }
            if ($reciveOutput && array_key_exists('signal', $reciveOutput) && $reciveOutput['signal']) {
                return response()->json(['result' => true, 'code' => $reciveOutput['signal']]);
            } else {
                return response()->json(['result' => false, 'code' => null]);
            }
        } else {
            return response()->json(['result' => false, 'code' => null]);
        }
    }
}
