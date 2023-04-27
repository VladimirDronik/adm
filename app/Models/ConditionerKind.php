<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConditionerKind extends Model
{
    protected $guarded = [];

    public function conditionerModels()
    {
        return $this->hasMany(ConditionerModel::class, 'kind', 'id');
    }

    public function conditionerCodes()
    {
        return $this->hasMany(ConditionerCode::class, 'kind', 'id');
    }
}
