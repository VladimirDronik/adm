<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtensionModule extends Model
{
    protected $guarded = ['id'];

    public function extensionModuleType()
    {
        return $this->belongsTo(ExtensionModuleType::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function ports()
    {
        return $this->hasMany(Port::class);
    }
}
