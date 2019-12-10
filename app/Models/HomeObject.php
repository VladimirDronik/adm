<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\HomeObject
 *
 * @property int $id
 * @property string $name название объекта
 * @property string $type
 * @property string $status
 * @property int $is_system
 * @property-read mixed $rus_type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Method[] $methods
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SchedulerTask[] $scheduler_tasks
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereIsSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereType($value)
 * @mixin \Eloquent
 */
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

    /**
     * Проверяет, уникально ли название $name в таблице объектов.
     * Если нет, то добавляет в конец названия подходящее для уникальности число (2, 3 и т.д.)
     *
     * @param int $object_id
     * @param string $name
     * @return string
     */
    public static function getUniqueObjectName(int $object_id, string $name): string
    {
        $index = 2;
        $unique_name = $name;
        while (HomeObject::where('id', '<>', $object_id)
            ->where('name', $unique_name)->exists()) {
            $unique_name = $name . ' ' .$index;
            $index++;
        }
        return $unique_name;
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
