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

    public function daliDevices(): HasMany
    {
        return $this->hasMany(DaliDevice::class, 'dali_gateway', 'id');
    }

    public function ledTapes(): HasMany
    {
        return $this->hasMany(LedTape::class, 'controller_id', 'id');
    }

    public function conditioners(): HasMany
    {
        return $this->hasMany(Conditioner::class, 'modbus_slaver_id', 'id');
    }

    public static function getWbLedOperModes(): array
    {
        return [
            0 => 'W+W+W+W',
            1 => '2W+W+W',
            2 => 'CCT+W+W',
            16 => 'W+W+2W',
            17 => '2W+2W',
            18 => 'CCT+2W',
            32 => 'W+W+CCT',
            33 => '2W+CCT',
            34 => 'CCT+CCT',
            256 => 'RGB+W',
            512 => '4W',
        ];
    }

    /**
     * Получить данные набора лед лент по коду выбранного режима
     */
    public function getLedTapesDataByCode(int $code): array
    {
        $tapesDataByCode = [
            0 => [
                ['name' => 'Канал 1', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '1', 'w' => 50],
                ['name' => 'Канал 2', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '2', 'w' => 50],
                ['name' => 'Канал 3', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '3', 'w' => 50],
                ['name' => 'Канал 4', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '4', 'w' => 50],
            ],
            1 => [
                ['name' => 'Канал 1,2', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '12', 'w' => 50],
                ['name' => 'Канал 3', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '3', 'w' => 50],
                ['name' => 'Канал 4', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '4', 'w' => 50],
            ],
            2 => [
                ['name' => 'Канал 1,2', 'controller_id' => $this->id, 'type' => LedTape::TYPE_CCT, 'channel' => '12', 'w' => 50, 'cct' => 50],
                ['name' => 'Канал 3', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '3', 'w' => 50],
                ['name' => 'Канал 4', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '4', 'w' => 50],
            ],
            16 => [
                ['name' => 'Канал 1', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '1', 'w' => 50],
                ['name' => 'Канал 2', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '2', 'w' => 50],
                ['name' => 'Канал 3,4', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '34', 'w' => 50],
            ],
            17 => [
                ['name' => 'Канал 1,2', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '12', 'w' => 50],
                ['name' => 'Канал 3,4', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '34', 'w' => 50],
            ],
            18 => [
                ['name' => 'Канал 1,2', 'controller_id' => $this->id, 'type' => LedTape::TYPE_CCT, 'channel' => '12', 'w' => 50, 'cct' => 50],
                ['name' => 'Канал 3,4', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '34', 'w' => 50],
            ],
            32 => [
                ['name' => 'Канал 1', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '1', 'w' => 50],
                ['name' => 'Канал 2', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '2', 'w' => 50],
                ['name' => 'Канал 3,4', 'controller_id' => $this->id, 'type' => LedTape::TYPE_CCT, 'channel' => '34', 'w' => 50, 'cct' => 50],
            ],
            33 => [
                ['name' => 'Канал 1,2', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '12', 'w' => 50],
                ['name' => 'Канал 3,4', 'controller_id' => $this->id, 'type' => LedTape::TYPE_CCT, 'channel' => '34', 'w' => 50, 'cct' => 50],
            ],
            34 => [
                ['name' => 'Канал 1,2', 'controller_id' => $this->id, 'type' => LedTape::TYPE_CCT, 'channel' => '12', 'w' => 50, 'cct' => 50],
                ['name' => 'Канал 3,4', 'controller_id' => $this->id, 'type' => LedTape::TYPE_CCT, 'channel' => '34', 'w' => 50, 'cct' => 50],
            ],
            256 => [
                ['name' => 'Канал 1,2,3', 'controller_id' => $this->id, 'type' => LedTape::TYPE_RGB, 'channel' => '123', 'h' => 0, 's' => 100, 'v' => 50],
                ['name' => 'Канал 4', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '4', 'w' => 50],
            ],
            512 => [
                ['name' => 'Канал 1,2,3,4', 'controller_id' => $this->id, 'type' => LedTape::TYPE_W, 'channel' => '1234', 'w' => 50],
            ],
        ];

        return $tapesDataByCode[$code] ?? [];
    }
}
