<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoilerGVS extends Model
{
    protected $table = 'boiler_gvs';

    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getTypes(bool $is_full = true)
    {
        $types = [
            'proterm' => 'Протерм',
            'evan' => 'Эван',
        ];

        return $is_full ? $types : array_keys($types);
    }

    public function getRusTypeAttribute()
    {
        return self::getTypes(true)[$this->type] ?? '';
    }

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}
