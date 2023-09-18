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

    public static function getTypeById($id)
    {
        return self::getFullTypeIds()[$id] ?? '';
    }

    public function getRusTypeAttribute()
    {
        return self::getTypeById($this->type);
    }

    public function getSingleRusTypeAttribute()
    {
        $rus_type = self::getTypeById($this->type);

        return mb_substr($rus_type, 0, mb_strlen($rus_type, 'UTF-8') - 2, 'UTF-8').'о';
    }
}
