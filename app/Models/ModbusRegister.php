<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModbusRegister extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = ['id'];

    const TYPE_COIL = 'coil';
    const TYPE_HOLDING = 'holding';
    const TYPE_INPUT = 'input';
    const TYPE_DISCRETE = 'discrete';

    public static function boot()
    {
        parent::boot();

        static::creating(function (ModbusRegister $register) {
            $register->timestamp = Carbon::now();
        });
    }

    public static function getTypes(): array
    {
        return [
            static::TYPE_COIL => 'coil',
            static::TYPE_HOLDING => 'holding',
            static::TYPE_INPUT => 'input',
            static::TYPE_DISCRETE => 'discrete',
        ];
    }

    public static function getSelectableDataFormat(): array
    {
        return [
            'bool' => 'bool',
            'u16' => 'u16',
            's16' => 's16',
            'u32' => 'u32',
            's32' => 's32',
            'string' => 'string',
        ];
    }

    public static function getSelectableAccess(): array
    {
        return [
            'ro' => 'ro',
            'rw' => 'rw',
        ];
    }

    public function slaver(): BelongsTo
    {
        return $this->belongsTo(ModbusSlaver::class, 'slaver_id', 'id');
    }
}
