<?php

namespace App\Services;

use App\Models\Usensor;
use App\Models\HomeObject;
use App\Models\Carbdioxide;
use Illuminate\Support\Facades\DB;

class CarbdioxideService
{
    public function __construct(
        private CarbdioxideObjectService $carbdioxideObjectService,
    ) {
    }

    public function prepare(Carbdioxide $carbdioxide, array $data)
    {
        $usensor = Usensor::where('id_object', $data['usensor_id'])->first();

        $carbdioxide->min_threshold = 400;
        switch ($usensor->type) {
            case Usensor::TYPE_SCD40:
                $carbdioxide->max_threshold = 2000;
                break;
            case Usensor::TYPE_SCD41:
                $carbdioxide->max_threshold = 5000;
                break;
        }

        $carbdioxide->name = $data['name'];
        $carbdioxide->mode = $data['mode'];
        $carbdioxide->optimal = $data['optimal'];
        $carbdioxide->gisteresis = $data['gisteresis'];
        $carbdioxide->min_alarm = $data['min_alarm'];
        $carbdioxide->max_alarm = $data['max_alarm'];
        $carbdioxide->object = $data['object'] ?? null;
        $carbdioxide->method_on = $data['method_on'] ?? null;
        $carbdioxide->method_off = $data['method_off'] ?? null;
        $carbdioxide->method_on_params = $data['method_on_params'] ?? null;
        $carbdioxide->method_off_params = $data['method_off_params'] ?? null;
        $carbdioxide->usensor_id = $data['usensor_id'];
        $carbdioxide->room = array_key_exists('room', $data) && $data['room'] != 0 ? $data['room'] : null;
    }

    /**
     * Создание датчика углекислого газа
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $carbdioxide = new Carbdioxide();

        $this->prepare($carbdioxide, $data);
        $carbdioxide->current = 0;
        $carbdioxide->is_system = $data['is_system'] ?? 0;

        DB::transaction(function () use ($carbdioxide) {
            $uniqueName = HomeObject::getUniqueObjectName(0, $carbdioxide->name);
            $object = $this->carbdioxideObjectService->createObject($uniqueName);
            $this->carbdioxideObjectService->createCheckMethodWithEvent($object->id);
            $carbdioxide->id_object = $object->id;
            $carbdioxide->save();
        });

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php check_carbdioxide.php '.$carbdioxide->id_object);

        return $carbdioxide->id;
    }

    /**
     * Удаление датчика углекислого газа
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $carbdioxide = Carbdioxide::findOrFail($id);

        $carbdioxide->relatedObject->delete();

        return true;
    }

    /**
     * Обновление датчика углекислого газа
     *
     * @throws \Throwable
     */
    public function update(Carbdioxide $carbdioxide, array $data): int
    {
        DB::transaction(function () use ($carbdioxide, $data) {
            $newName = trim($data['name']);
            if ($carbdioxide->name != $newName) {
                $carbdioxide->relatedObject->name = HomeObject::getUniqueObjectName($carbdioxide->id_object, $newName);
                $carbdioxide->relatedObject->save();
            }

            $this->prepare($carbdioxide, $data);
            $carbdioxide->save();
        });

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php check_carbdioxide.php '.$carbdioxide->id_object);

        return $carbdioxide->id;
    }
}
