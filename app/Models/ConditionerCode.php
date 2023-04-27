<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConditionerCode extends Model
{
    protected $guarded = ['id'];

    public function conditionerKind()
    {
        return $this->belongsTo(ConditionerKind::class, 'kind', 'id');
    }
}
