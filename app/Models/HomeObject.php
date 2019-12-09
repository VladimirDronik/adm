<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeObject extends Model
{
    protected $table = 'objects';
    public $timestamps = false;

    public static function getFullTypeIds()
    {
        return ObjType::orderBy('label')->pluck('label', 'name')->toArray();
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

    /* relations */

    public function methods()
    {
        return $this->hasMany(Method::class, 'id_object', 'id')->orderBy('id');
    }

    public function scheduler_tasks()
    {
        return $this->hasMany(SchedulerTask::class, 'object', 'id')->orderBy('id');
    }
}
