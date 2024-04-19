<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConditionerType extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function conditioners()
    {
        return $this->hasMany(Conditioner::class, 'type', 'id');
    }
}
