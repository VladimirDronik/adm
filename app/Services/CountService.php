<?php

namespace App\Services;

use App\Models\Count;
use App\Models\HomeObject;
use App\Models\Method;
use App\Models\SchedulerTask;
use Illuminate\Support\Facades\DB;

class CountService {

    private $count_object_service;

    public function __construct(CountObjectService $count_object_service)
    {
        $this->count_object_service = $count_object_service;
    }

    /**
     * Удаление объекта, созданного автоматически для счетчика.
     * Удаление событий для системных методов этого объекта.
     * Удаление методов происходит автоматически на уровне базы (по связям объекта).
     *
     * @param int $object_id
     * @throws \Exception
     */
    public function deleteAutoObject(int $object_id)
    {
        $methods = Method::where('is_system', 1)
            ->where('id_object', $object_id)->get();
        SchedulerTask::whereIn('method', $methods->pluck('id')->toArray())->delete();
        HomeObject::destroy($object_id);
    }

    /**
     * Удаление счетчика. Если связанный объект системный, то удаление объекта, методов, событий,
     * созданных автоматически при создании счетчика
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $count = Count::findOrFail($id);

        if ($count->object && $count->object->is_system) {
            DB::transaction(function () use (&$count) {
                $this->deleteAutoObject($count->id_object);
                $count->delete();
            });
        } else {
            $count->delete();
        }

        return true;
    }

    public function prepareCount(Count $count, array $data)
    {
        $count->name = trim($data['name']);
        if (isset($data['type'])) {
            $count->type = $data['type'];
        }
        $count->id_object = (int)$data['id_object'];
        $count->impulse = $data['impulse'];
        if (isset($data['unit'])) {
            $count->unit = trim($data['unit']);
        }
        $count->today_value = $data['today_value'] ?? 0;
        $count->total_value = $data['total_value'] ?? 0;
    }

    /**
     * Создание счетчика. Если $data['type'] === 'auto',
     * то еще создается объект с методами и событиями.
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $count = new Count();
        $this->prepareCount($count, $data);

        if ($data['object_type'] === 'manual') {
            $count->save();
        } else if ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$count) {
                $unique_name = HomeObject::getUniqueObjectName(0, $count->name);
                $object = $this->count_object_service->createCountObject($unique_name);
                $this->count_object_service->createCountObjectMethodsWithEvents($object->id);
                $count->id_object = $object->id;
                $count->save();
            });
        }

        return $count->id;
    }

    private function isUpdateAutoObjectName(Count $count, string $name): bool
    {
        return $count->name !== trim($name) && $count->object && $count->object->is_system;
    }

    /**
     * Обновление счетчика. Если изменилось название и у счетчика системный объект,
     * то изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @param Count $count
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Count $count, array $data): int
    {
        DB::transaction(function () use (&$count, $data) {
            if ($this->isUpdateAutoObjectName($count, $data['name'])) {
                $count->object->name = HomeObject::getUniqueObjectName($count->object->id, trim($data['name']));
                $count->object->save();
            }
            $this->prepareCount($count, $data);
            $count->save();
        });

        return $count->id;
    }
}