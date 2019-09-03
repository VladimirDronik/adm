<?php

namespace App\Traits\Models;

trait SchedulerPointType
{
    public static function getFullTypeIds()
    {
        return [
            self::TYPE_CRON => 'Ежеминутные',
            self::TYPE_DAYS => 'Ежедневные',
            self::TYPE_MONTHS => 'Ежемесячные',
            self::TYPE_YEARS => 'Ежегодные',
        ];
    }

    public static function getTypeIds()
    {
        return array_keys(self::getFullTypeIds());
    }

    public static function getTypeById($id) {
        return self::getFullTypeIds()[$id] ?? '';
    }

    public function getRusTypeAttribute()
    {
        return self::getTypeById($this->type);
    }
}
