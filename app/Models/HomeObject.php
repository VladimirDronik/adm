<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\HomeObject
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name название объекта
 * @property string $type
 * @property string $status
 * @property int|null $view
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereView($value)
 * @property-read \App\Models\View|null $eview
 * @property-read mixed $rus_type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Method[] $methods
 */
class HomeObject extends Model
{
    const TYPE_BUTTON = 'button';
    const TYPE_LAMP = 'lamp';
    const TYPE_TERMO = 'termo';
    const TYPE_HYDRO = 'hydro';
    const TYPE_SOCKET = 'socket';

    protected $table = 'objects';
    public $timestamps = false;

    public static function getFullTypeIds()
    {
        return [
            self::TYPE_BUTTON => 'Кнопка',
            self::TYPE_LAMP => 'Лампа',
            self::TYPE_SOCKET => 'Розетка',
            self::TYPE_TERMO => 'Термометр',
            self::TYPE_HYDRO => 'Гигрометр'
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
