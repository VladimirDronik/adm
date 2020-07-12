<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\HomeObject;

class Lamp extends Model
{
    const TYPE_LAMP = 'lamp';

    protected $table = 'lamps';
    public $timestamps = false;


    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_LAMP => 'Лампа'
        ];

        return $is_full ? $types : array_keys($types);
    }

    public function getRusTypeAttribute()
    {
        return self::getTypes(true)[$this->type] ?? '';
    }

    /* relations */

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
