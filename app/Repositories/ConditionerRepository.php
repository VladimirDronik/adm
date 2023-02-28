<?php

namespace App\Repositories;

use App\Models\Conditioner;
use App\Models\ConditionerCode;
use App\Models\ConditionerModel;
use App\Models\ConditionerVendor;

class ConditionerRepository
{
    public function getAll($pagination_count = 30)
    {
        return Conditioner::paginate($pagination_count);
    }

    public function getAllVendorsToArray()
    {
        return ConditionerVendor::all()->pluck('name', 'id')->toArray();
    }

    public function getModelsByVendor(int $vendorId)
    {
        return ConditionerModel::where('vendor', $vendorId)->get();
    }

    public function getCode(int $kind, string $operationMode, string $fanMode, float $temp)
    {
        return ConditionerCode::where('kind', $kind)
            ->where('operationMode', $operationMode)
            ->where('fanMode', $fanMode)
            ->where('temperature', $temp)
            ->first();
    }
}