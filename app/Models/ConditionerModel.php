<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConditionerModel extends Model
{
    protected $guarded = ['id'];

    public function conditioners()
    {
        return $this->hasMany(Conditioner::class, 'model', 'id');
    }

    public function conditionerVendor()
    {
        return $this->belongsTo(ConditionerVendor::class, 'vendor', 'id');
    }

    public function conditionerKind()
    {
        return $this->belongsTo(ConditionerKind::class, 'kind', 'id');
    }
}
