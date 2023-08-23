<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\ObjType;
use App\Models\YandexStation;
use App\Repositories\RoomRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class YandexStationService
{
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function store(array $data): bool
    {
        $station = YandexStation::where('speaker_id', $data['iot_id'])->first();

        if (!$station) {
            $station = new YandexStation();
        }

        $station->name = $data['name'];
        $station->platform = $data['platform'];
        $station->device_id = $data['device_id'];
        $station->speaker_id = $data['iot_id'];
        $station->scenario_id = $data['scenario_id'];
        $station->active = 1;

        $room = $this->roomRepository->getByName($data['room']);
        if ($room) {
            $station->room = $room->id;
        } else {
            $station->room = null;
        }

        $object = $this->updateOrCreateStationObject($station);

        DB::transaction(function () use ($station, $object) {
            $object->save();
            $station->object()->associate($object);
            $station->save();
        });

        return true;
    }

    public function update(YandexStation $station, array $data)
    {
        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }

        $station->fill($data);
        $object = $this->updateOrCreateStationObject($station);

        DB::transaction(function () use ($station, $object) {
            $object->save();
            $station->object()->associate($object);
            $station->save();
        });

        return $station->id;
    }

    public function delete(int $idStation)
    {
        $station = YandexStation::findOrFail($idStation);
        $station->delete();

        return true;
    }

    /**
     * Обновление или создание объекта станции
     *
     * @param YandexStation $station
     * @return Model
     */
    private function updateOrCreateStationObject(YandexStation $station): Model
    {
        $object = $station->object;

        if (!$object) {
            $object = new HomeObject();
            $unique_name = HomeObject::getUniqueObjectName(0, $station->name);

            $object->type = ObjType::TYPE_YANDEX_STATION;
            $object->is_system = 0;
            $object->status = 'on';
        } else {
            $unique_name = HomeObject::getUniqueObjectName($object->id, $station->name);
        }

        $object->name = $unique_name;

        return $object;
    }
}