<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorGraph extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function sensorsParam()
    {
        return $this->belongsTo(SensorsParam::class, 'param_id', 'id');
    }
}
