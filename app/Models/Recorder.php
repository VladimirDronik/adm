<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recorder extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    const VENDOR_HIKVISION_HIWATCH = 'HikVision/HiWatch';

    public static function getVendors()
    {
        $vendors = [
            static::VENDOR_HIKVISION_HIWATCH => static::VENDOR_HIKVISION_HIWATCH,
        ];

        return $vendors;
    }

    public function cameras()
    {
        return $this->hasMany(Camera::class)->orderBy('sort');
    }
}
