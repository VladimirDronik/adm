<?php

namespace App\Services;

use App\Models\Lightstat;
use App\Models\HomeObject;
use Illuminate\Support\Facades\DB;

class LightstatService
{
    public function __construct(
        private LightstatObjectService $lightstatObjectService,
    ) {
    }

    public function prepare(Lightstat $lightstat, array $data)
    {
        $data['min_threshold'] = 0;
        $data['max_threshold'] = 54612;

        $data['room'] = array_key_exists('room', $data) && $data['room'] != 0 ? $data['room'] : null;

        $lightstat->fill($data);
    }

    /**
     * Создание датчика освещенности.
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $lightstat = new Lightstat();

        $this->prepare($lightstat, $data);
        $lightstat->current = 0;

        DB::transaction(function () use (&$lightstat) {
            $uniqueName = HomeObject::getUniqueObjectName(0, $lightstat->name);
            $object = $this->lightstatObjectService->createLightstatObject($uniqueName);
            $this->lightstatObjectService->createLightstatObjectMethodsWithEvents($object->id);
            $lightstat->id_object = $object->id;
            $lightstat->save();
        });

        chdir(env('SERVER_FOLDER') . '/scripts');
        exec('php check_lightstat.php ' . $lightstat->id_object);

        return $lightstat->id;
    }

    /**
     * Удаление датчика освещенности.
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $lightstat = Lightstat::findOrFail($id);

        if ($lightstat->id_object) {
            DB::transaction(function () use ($lightstat) {
                $lightstat->iobject->delete();
                $lightstat->delete();
            });
        } else {
            $lightstat->delete();
        }

        return true;
    }

    /**
     * Обновление датчика освещенности
     *
     * @throws \Throwable
     */
    public function update(Lightstat $lightstat, array $data): int
    {
        DB::transaction(function () use (&$lightstat, $data) {
            $name = trim($data['name']);

            if ($lightstat->name != $name) {
                $lightstat->iobject->name = HomeObject::getUniqueObjectName($lightstat->id_object, $name);
                $lightstat->iobject->save();
            }

            $this->prepare($lightstat, $data);
            $lightstat->save();
        });

        chdir(env('SERVER_FOLDER') . '/scripts');
        exec('php check_lightstat.php ' . $lightstat->id_object);

        return $lightstat->id;
    }
}
