<?php

namespace App\Services;

use App\Models\Hygrostat;
use App\Models\HomeObject;
use Illuminate\Support\Facades\DB;

class HygrostatService
{
    public function __construct(
        private HygrostatObjectService $hygrostatObjectService,
    ) {
    }

    /**
     * Удаление датчика влажности.
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $hygrostat = Hygrostat::findOrFail($id);

        if ($hygrostat->id_object) {
            DB::transaction(function () use ($hygrostat) {
                $hygrostat->iobject->delete();
                $hygrostat->delete();
            });
        } else {
            $hygrostat->delete();
        }

        return true;
    }

    public function prepare(Hygrostat $hygrostat, array $data)
    {
        $data['min_threshold'] = '0';
        $data['max_threshold'] = '100';

        $data['room'] = array_key_exists('room', $data) && $data['room'] != 0 ? $data['room'] : null;

        $hygrostat->fill($data);
    }

    /**
     * Создание датчика влажности
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $hygrostat = new Hygrostat();

        $this->prepare($hygrostat, $data);

        DB::transaction(function () use (&$hygrostat) {
            $uniqueName = HomeObject::getUniqueObjectName(0, $hygrostat->name);
            $object = $this->hygrostatObjectService->createHygrostatObject($uniqueName);
            $this->hygrostatObjectService->createHygrostatObjectMethodsWithEvents($object->id);

            $hygrostat->id_object = $object->id;
            $hygrostat->save();
        });

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php check_hygrostat.php '.$hygrostat->id_object);

        return $hygrostat->id;
    }

    /**
     * Обновление датчика влажности.
     *
     * @throws \Throwable
     */
    public function update(Hygrostat $hygrostat, array $data): int
    {
        DB::transaction(function () use (&$hygrostat, $data) {
            $name = trim($data['name']);

            if ($hygrostat->name != $name) {
                $hygrostat->iobject->name = HomeObject::getUniqueObjectName($hygrostat->id_object, $name);
                $hygrostat->iobject->save();
            }

            $this->prepare($hygrostat, $data);
            $hygrostat->save();
        });

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php check_hygrostat.php '.$hygrostat->id_object);

        return $hygrostat->id;
    }
}
