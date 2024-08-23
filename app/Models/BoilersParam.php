<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoilersParam extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    public function boiler(): BelongsTo
    {
        return $this->belongsTo(Boiler::class);
    }
}
