<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Regulator extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function object(): BelongsTo
    {
        return $this->belongsTo(HomeObject::class);
    }

    public function relatedRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room');
    }

    public function sensorsParam(): BelongsTo
    {
        return $this->belongsTo(SensorsParam::class);
    }

    public function lowerMethod(): BelongsTo
    {
        return $this->belongsTo(Method::class, 'lower_method');
    }

    public function higherMethod(): BelongsTo
    {
        return $this->belongsTo(Method::class, 'higher_method');
    }

    public function fallbackMethod(): BelongsTo
    {
        return $this->belongsTo(Method::class, 'fallback_method');
    }
}
