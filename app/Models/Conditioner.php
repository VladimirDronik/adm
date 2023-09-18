<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Conditioner
 *
 * @mixin \Eloquent
 *
 * @property-read mixed $rus_type
 */
class Conditioner extends Model
{
    protected $guarded = ['id'];

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function conditionerModel()
    {
        return $this->belongsTo(ConditionerModel::class, 'model', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room', 'id');
    }
}
