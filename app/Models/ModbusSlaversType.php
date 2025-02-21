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

    public static function getPurposes(): array
    {
        return [
            'ac' => 'ac',
            'ir' => 'ir',
            'light' => 'light',
            'thermostat' => 'thermostat',
            'heat' => 'heat',
            'relay' => 'relay',
            'meter' => 'meter',
            'other' => 'other',
        ];
    }

    public static function getProtocols(): array
    {
        return [
            'modbus' => 'Modbus',
            'pulsarm' => 'Пульсар-М',
        ];
    }
}
