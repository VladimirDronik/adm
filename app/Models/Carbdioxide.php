<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Carbdioxide extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getFullCarbdioxideIds()
    {
        return [
            0 => 'Реакция на уменьшение углекислого газа',
            1 => 'Реакция на увеличение углекислого газа',
        ];
    }

    public function getRusCarbdioxideAttribute()
    {
        return static::getFullCarbdioxideIds()[$this->mode] ?? '';
    }

    public function relatedObject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function influenceObject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function methodOn()
    {
        return $this->belongsTo(Method::class, 'method_on', 'id');
    }

    public function methodOff()
    {
        return $this->belongsTo(Method::class, 'method_off', 'id');
    }

    public function graphs()
    {
        return $this->hasMany(GraphCarbdioxide::class, 'id_carbdioxide', 'id')->orderBy('datetime');
    }

    public function lastGraphs()
    {
        return $this->hasMany(GraphCarbdioxide::class, 'id_carbdioxide', 'id')
            ->where('datetime', '>=', Carbon::now()->subDays(7))
            ->orderBy('datetime');
    }
}
