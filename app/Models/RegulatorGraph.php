<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegulatorGraph extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function regulator(): BelongsTo
    {
        return $this->belongsTo(Regulator::class, 'regulator_id', 'id');
    }
}
