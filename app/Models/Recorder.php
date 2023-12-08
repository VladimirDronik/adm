<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recorder extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    const VENDOR_HIKVISION_HIWATCH = 'HikVision/HiWatch';
    const VENDOR_OTHER = 'other';

    public static function getVendors()
    {
        $vendors = [
            static::VENDOR_HIKVISION_HIWATCH => static::VENDOR_HIKVISION_HIWATCH,
            static::VENDOR_OTHER => 'Другой',
        ];

        return $vendors;
    }

    public function getVendorNameAttribute()
    {
        $vendors = static::getVendors();

        return array_key_exists($this->vendor, $vendors) ? $vendors[$this->vendor] : '';
    }

    public function cameras()
    {
        return $this->hasMany(Camera::class)->orderBy('sort');
    }
}
