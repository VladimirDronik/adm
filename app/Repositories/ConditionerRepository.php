<?php

namespace App\Repositories;

use App\Models\Conditioner;
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

    public function getModelsByVendorToArray(int $vendorId)
    {
        return ConditionerModel::where('vendor', $vendorId)->pluck('name', 'id')->toArray();
    }
}