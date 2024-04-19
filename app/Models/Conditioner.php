<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conditioner extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room', 'id');
    }

    public function modbusSlaver()
    {
        return $this->belongsTo(ModbusSlaver::class, 'modbus_slaver_id', 'id');
    }

    public function relatedType()
    {
        return $this->belongsTo(ConditionerType::class, 'type', 'id');
    }
}
