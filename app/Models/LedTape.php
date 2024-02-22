<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedTape extends Model
{
    protected $table = 'tapes';
    public $timestamps = false;
    protected $guarded = ['id'];

    const TYPE_RGB = 'RGB';
    const TYPE_RGBW = 'RGBW';
    const TYPE_W = 'W';
    const TYPE_CCT = 'CCT';

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_RGB => 'RGB',
            self::TYPE_RGBW => 'RGBW',
            self::TYPE_W => 'W',
            self::TYPE_CCT => 'CCT',
        ];

        return $is_full ? $types : array_keys($types);
    }

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function relatedRoom()
    {
        return $this->belongsTo(Room::class, 'room', 'id');
    }

    public function modbusSlaver()
    {
        return $this->belongsTo(ModbusSlaver::class, 'controller_id', 'id');
    }

    function hsvToHsl()
    {
        $s = $this->s / 100; // Переводим S из процентов в дробное число от 0 до 1
        $v = $this->v / 100; // Переводим V из процентов в дробное число от 0 до 1

        $l = (2 - $s) * $v / 2;

        if ($l != 0) {
            if ($l == 1) {
                $s = 0;
            } elseif ($l < 0.5) {
                $s = round($s * $v / ($l * 2) * 100);
            } else {
                $s = round($s * $v / (2 - $l * 2) * 100);
            }
        } else {
            $s = 0;
        }

        $l = round($l * 100);

        return ['h' => $this->h, 's' => $s, 'l' => $l];
    }
}
