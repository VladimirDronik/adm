<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConditionerVendor extends Model
{
    protected $guarded = ['id'];

    public function conditionerModels()
    {
        return $this->hasMany(ConditionerModel::class, 'vendor', 'id');
    }
}
