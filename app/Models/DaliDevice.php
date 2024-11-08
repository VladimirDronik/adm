<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaliDevice extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

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
        return $this->belongsTo(ModbusSlaver::class, 'dali_gateway', 'id');
    }

    public function daliDevices()
    {
        return $this->belongsToMany(
            DaliDevice::class, 'dali_device_group',
            'group_id', 'dali_device_id',
            'id_object', 'id_object'
        );
    }

    public function groups()
    {
        return $this->belongsToMany(
            DaliDevice::class, 'dali_device_group',
            'dali_device_id', 'group_id',
            'id_object', 'id_object'
        );
    }
}
