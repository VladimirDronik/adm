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

        if ($r->temp == 'off') {
            $conditionerCode = $this->conditionersRep
                ->getOffCode((int)$r->kind, (string)$r->temp);
        } else {
            $conditionerCode = $this->conditionersRep
                ->getCode((int)$r->kind, (string)$r->operationMode, (string)$r->fanMode, (float)$r->temp);
        }

        return response()->json(['result' => true, 'code' => $conditionerCode ? $conditionerCode->code : '']);
    }

    public function readCode(Request $r)
    {
        abort_if(!ajaxHas($r, ['wbMir', 'ip']), 400);

        $command = 'rs_control ir_scan -r -d wb-mir --ip ' . $r->ip . ' -u ' . $r->wbMir;

        $output = null;

        exec($command, $output);

        $response = $output ? json_decode($output[0], true) : null;

        if ($response && !$response['error_code']) {
            return response()->json(['result' => true]);
        } else {
            return response()->json(['result' => false, 'error' => $response['error_text']]);
        }
    }

    public function reciveCode(Request $r)
    {
        abort_if(!ajaxHas($r, ['wbMir', 'ip']), 400);

        $command = 'rs_control ir_scan -g -d wb-mir --ip ' . $r->ip . ' -u ' . $r->wbMir;

        $output = null;

        exec($command, $output);

        $response = $output ? json_decode($output[0], true) : null;

        if ($response && !$response['error_code']) {
            return response()->json(['result' => true, 'code' => $response['signal']]);
        } else {
            return response()->json(['result' => false, 'code' => null, 'error' => $response['error_text']]);
        }
    }

    public function saveCode(Request $r)
    {
        abort_if(!ajaxHas($r, ['kind', 'operationMode', 'fanMode', 'temp', 'code']), 400);

        try {
            if ($r->temp == 'off') {
                $conditionerCode = $this->conditionersRep->getOffCode((int)$r->kind, (string)$r->temp);
                $this->conditionersRep
                    ->updateOrCreate($conditionerCode ?: null, (string)$r->code, (int)$r->kind, null, null, null, true);
            } else {
                $conditionerCode = $this->conditionersRep
                    ->getCode((int)$r->kind, (string)$r->operationMode, (string)$r->fanMode, (float)$r->temp);
                $this->conditionersRep
                    ->updateOrCreate($conditionerCode ?: null, (string)$r->code, (int)$r->kind, (string)$r->operationMode, (string)$r->fanMode, (float)$r->temp);
            }

            return response()->json(['result' => true]);
        } catch (\Throwable $th) {
            return response()->json(['result' => false]);
        }
    }

    public function cancelReadingCode(Request $r)
    {
        abort_if(!ajaxHas($r, ['wbMir', 'ip']), 400);

        $command = 'rs_control ir_scan --cancel_scan -d wb-mir --ip ' . $r->ip . ' -u ' . $r->wbMir;

        $output = null;

        exec($command, $output);

        $response = $output ? json_decode($output[0], true) : null;

        if ($response && !$response['error_code']) {
            return response()->json(['result' => true]);
        } else {
            return response()->json(['result' => false, 'error' => $response['error_text']]);
        }
    }
}
