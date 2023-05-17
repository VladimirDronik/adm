<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\ConditionerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ConditionerService;

class ConditionerController extends Controller
{
    private $conditionersRep;
    private $service;

    public function __construct(ConditionerRepository $conditionersRep, ConditionerService $service)
    {
        $this->conditionersRep = $conditionersRep;
        $this->service = $service;
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

        $cancelResponse = $this->service->cancelReadCommand($r->ip, $r->wbMir);

        if (!$cancelResponse || $cancelResponse['error_code']) {
            return response()->json([
                'result' => false,
                'error' => $cancelResponse ? $cancelResponse['error_text'] : 'Неизвестная ошибка отмены считывания'
            ]);
        }

        $response = $this->service->startReadCommand($r->ip, $r->wbMir);

        if ($response && !$response['error_code']) {
            return response()->json(['result' => true]);
        } else {
            return response()->json([
                'result' => false,
                'error' => $response ? $response['error_text'] : 'Неизвестная ошибка запуска считывания'
            ]);
        }
    }

    public function reciveCode(Request $r)
    {
        abort_if(!ajaxHas($r, ['wbMir', 'ip']), 400);

        $response = $this->service->reciveCodeCommand($r->ip, $r->wbMir);

        if ($response && !$response['error_code']) {
            return response()->json(['result' => true, 'code' => $response['signal']]);
        } else {
            return response()->json([
                'result' => false,
                'code' => null,
                'error' => $response ? $response['error_text'] : 'Неизвестная ошибка получения кода']);
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

        $response = $this->service->cancelReadCommand($r->ip, $r->wbMir);

        if ($response && !$response['error_code']) {
            return response()->json(['result' => true]);
        } else {
            return response()->json([
                'result' => false,
                'error' => $response ? $response['error_text'] : 'Неизвестная ошибка отмены считывания'
            ]);
        }
    }
}
