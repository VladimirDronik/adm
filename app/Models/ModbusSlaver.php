<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModbusSlaver extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = ['id'];

    public function relatedBus(): BelongsTo
    {
        return $this->belongsTo(ModbusBus::class, 'bus', 'id');
    }

    public function relatedType(): BelongsTo
    {
        return $this->belongsTo(ModbusSlaversType::class, 'type', 'id');
    }

    public function registers(): HasMany
    {
        return $this->hasMany(ModbusRegister::class, 'slaver_id', 'id');
    }
}
