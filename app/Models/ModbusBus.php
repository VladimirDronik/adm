<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModbusBus extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = ['id'];

    const TYPE_RTU = 'rtu';
    const TYPE_TCP = 'tcp';

    public static function getTypes(): array
    {
        return [
            static::TYPE_RTU => 'RTU',
            static::TYPE_TCP => 'TCP',
        ];
    }

    public function getTypeNameAttribute()
    {
        $types = static::getTypes();

        return array_key_exists($this->type, $types) ? $types[$this->type] : '';
    }

    public static function getSelectableBaudrate(): array
    {
        return [
            110 => 110,
            150 => 150,
            300 => 300,
            600 => 600,
            1200 => 1200,
            2400 => 2400,
            4800 => 4800,
            9600 => 9600,
            19200 => 19200,
            38400 => 38400,
            57600 => 57600,
            115200 => 115200,
        ];
    }

    public static function getSelectableParity(): array
    {
        return [
            'none' => 'none',
            'odd' => 'odd',
            'even' => 'even',
        ];
    }

    public static function getSelectableStopbits(): array
    {
        return [
            1 => '1',
            2 => '2',
        ];
    }

    public static function getSelectableDevice(): array
    {
        $ttyUsbFiles = glob('/dev/ttyUSB*');
        $devices = [];

        if (!empty($ttyUsbFiles)) {
            foreach ($ttyUsbFiles as $ttyUsbFile) {
                if (!static::where('device', $ttyUsbFile)->exists()) {
                    $devices[$ttyUsbFile] = $ttyUsbFile;
                }
            }
        }

        return $devices;
    }

    public function slavers(): HasMany
    {
        return $this->hasMany(ModbusSlaver::class, 'bus', 'id');
    }
}
