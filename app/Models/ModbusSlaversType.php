<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModbusSlaversType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    public function slavers(): HasMany
    {
        return $this->hasMany(ModbusSlaver::class, 'type', 'id');
    }
}
