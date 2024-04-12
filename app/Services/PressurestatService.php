<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Pressurestat;
use Illuminate\Support\Facades\DB;
use App\Repositories\PortRepository;

class PressurestatService
{
    public function __construct(
        private PressurestatObjectService $pressurestatObjectService,
        private PortRepository $portRepository,
        private PortService $portService
    ) {
    }

    public function prepare(Pressurestat $pressurestat, array $data)
    {
        switch ($data['type_sensor']) {
            case Pressurestat::TYPE_BMX280:
                $pressurestat->min_threshold = 600;
                $pressurestat->max_threshold = 820;
                break;
            case Pressurestat::TYPE_PTSENSOR:
                $pressurestat->min_threshold = 0;
                $pressurestat->max_threshold = 10000;
                break;
        }

        $pressurestat->name = $data['name'];
        $pressurestat->mode = $data['mode'];
        $pressurestat->type_sensor = $data['type_sensor'];
        $pressurestat->optimal = $data['optimal'];
        $pressurestat->gisteresis = $data['gisteresis'];
        $pressurestat->min_alarm = $data['min_alarm'];
        $pressurestat->max_alarm = $data['max_alarm'];
        $pressurestat->object = $data['object'] ?? null;
        $pressurestat->method_on = $data['method_on'] ?? null;
        $pressurestat->method_off = $data['method_off'] ?? null;
        $pressurestat->method_on_params = $data['method_on_params'] ?? null;
        $pressurestat->method_off_params = $data['method_off_params'] ?? null;
        $pressurestat->usensor_id = $data['usensor_id'];
        $pressurestat->room = array_key_exists('room', $data) && $data['room'] != 0 ? $data['room'] : null;
    }

    /**
     * Создание датчика давления
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $pressurestat = new Pressurestat();

        $this->prepare($pressurestat, $data);
        $pressurestat->current = 0;

        DB::transaction(function () use ($pressurestat) {
            $uniqueName = HomeObject::getUniqueObjectName(0, $pressurestat->name);
            $object = $this->pressurestatObjectService->createObject($uniqueName);
            $this->pressurestatObjectService->createCheckMethodWithEvent($object->id);
            $pressurestat->id_object = $object->id;
            $pressurestat->save();
        });

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php check_pressure.php ' . $pressurestat->id_object);

        return $pressurestat->id;
    }

    /**
     * Удаление датчика давления
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $pressurestat = Pressurestat::findOrFail($id);

        $pressurestat->relatedObject->delete();

        return true;
    }

    /**
     * Обновление датчика давления
     *
     * @throws \Throwable
     */
    public function update(Pressurestat $pressurestat, array $data): int
    {
        DB::transaction(function () use ($pressurestat, $data) {
            $newName = trim($data['name']);
            if ($pressurestat->name != $newName) {
                $pressurestat->relatedObject->name = HomeObject::getUniqueObjectName($pressurestat->id_object, $newName);
                $pressurestat->relatedObject->save();
            }

            $this->prepare($pressurestat, $data);
            $pressurestat->save();
        });

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php check_pressure.php ' . $pressurestat->id_object);

        return $pressurestat->id;
    }
}
