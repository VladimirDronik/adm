<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereIsSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HomeObject whereType($value)
 *
 * @mixin \Eloquent
 *
 * @property-read int|null $methods_count
 * @property-read int|null $scheduler_tasks_count
 */
class HomeObject extends Model
{
    const GATEWAY_HTTP = 'http';
    const GATEWAY_MODBUS = 'modbus';

    protected $table = 'objects';
    public $timestamps = false;
    protected $guarded = ['id'];

    public static function getGatewayTypes(): array
    {
        return [
            static::GATEWAY_MODBUS => 'modbus',
            static::GATEWAY_HTTP => 'http',
        ];
    }

    public static function getFullTypeIds()
    {
        return ObjType::orderBy('label')->pluck('label', 'name')->toArray();
    }

    public static function getTypeIds()
    {
        return array_keys(self::getFullTypeIds());
    }

    public static function getTypeById($id)
    {
        return self::getFullTypeIds()[$id] ?? '';
    }

    /**
     * Проверяет, уникально ли название $name в таблице объектов.
     * Если нет, то добавляет в конец названия подходящее для уникальности число (2, 3 и т.д.)
     */
    public static function getUniqueObjectName(int $object_id, string $name): string
    {
        $index = 2;
        $unique_name = $name;
        while (HomeObject::where('id', '<>', $object_id)
            ->where('name', $unique_name)->exists()) {
            $unique_name = $name.' '.$index;
            $index++;
        }

        return $unique_name;
    }

    /**
     * Метод для удаления объекта, созданного автоматически для термостата, счетчика или диммера
     * Используется ли объект еще в какой-либо таблице,
     * кроме таблицы $except_table_name в записи с id = $except_id.
     * Methods и Scheduler_tasks не проверяются.
     */
    public static function isObjectUsed(int $object_id, int $except_id, string $except_table_name): bool
    {
        $object_map = [
            'counts' => 'id_object',
            'ports' => 'object',
            'termostats' => ['id_object', 'object'],
            'view_items' => 'id_object',
            'dimmers' => 'id_object',
            //'methods' => 'id_object', // не надо проверять, так как есть методы объекта
            //'scheduler_tasks' => 'object', // не надо проверять, так как есть события объекта
        ];

        foreach ($object_map as $table_name => $column_names) {
            if (is_string($column_names)) {
                $column_names = [$column_names];
            }
            foreach ($column_names as $column_name) {
                if ($except_table_name === $table_name) {
                    if (DB::table($table_name)->where('id', '<>', $except_id)
                        ->where($column_name, $object_id)->exists()) {
                        return true;
                    }
                } else {
                    if (DB::table($table_name)->where($column_name, $object_id)->exists()) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Удаление объекта, созданного автоматически для счетчика, термостата, универсального датчика или диммера.
     * Удаление событий для системных методов этого объекта.
     * Удаление методов происходит автоматически на уровне базы (по связям объекта).
     *
     * @throws \Exception
     */
    public static function deleteAutoObject(int $object_id)
    {
        // $methods = Method::where('is_system', 1)
        //   ->where('id_object', $object_id)->get();
        // SchedulerTask::whereIn('method', $methods->pluck('id')->toArray())->delete();
        HomeObject::destroy($object_id);
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

    public function conditioner()
    {
        return $this->hasOne(Conditioner::class, 'id_object', 'id');
    }

    public function curtein()
    {
        return $this->hasOne(Curtain::class, 'id_object', 'id');
    }

    public function usensor()
    {
        return $this->hasOne(Usensor::class, 'id_object', 'id');
    }

    public function boilerManual()
    {
        return $this->hasOne(BoilerManual::class, 'id_object', 'id');
    }

    public function boilerAuto()
    {
        return $this->hasMany(BoilerAuto::class, 'id_object', 'id');
    }

    public function labels()
    {
        return $this->hasMany(Label::class, 'id_object', 'id');
    }
}
