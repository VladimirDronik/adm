<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    protected $guarded = ['id'];

    const VENDOR_IVIDEON = 'ivideon';

    const VENDOR_HIKVISION_HIWATCH = 'HikVision/HiWatch';

    const VENDOR_OTHER = 'other';

    const TYPE_DIRECT_LINK = 'direct_link';

    const TYPE_MEDIA_SERVER = 'media_server';

    public static function getVendors()
    {
        $vendors = [
            static::VENDOR_IVIDEON => 'iVideon',
            static::VENDOR_HIKVISION_HIWATCH => static::VENDOR_HIKVISION_HIWATCH,
            static::VENDOR_OTHER => 'Другой',
        ];

        return $vendors;
    }

    public static function getTypes()
    {
        $types = [
            static::TYPE_DIRECT_LINK => 'Прямая ссылка',
            static::TYPE_MEDIA_SERVER => 'Медиа сервер (RTSP поток)',
        ];

        return $types;
    }

    public function getVendorNameAttribute()
    {
        $vendors = static::getVendors();

        return array_key_exists($this->vendor, $vendors) ? $vendors[$this->vendor] : '';
    }

    public function getTypeNameAttribute()
    {
        $types = static::getTypes();

        return array_key_exists($this->type, $types) ? $types[$this->type] : '';
    }

    public function recorder()
    {
        return $this->belongsTo(Recorder::class);
    }
}
