<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\ObjType;
use App\Models\Script;
use App\Models\YandexStation;
use App\Repositories\RoomRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use ScriptsTableSeeder;

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
            $this->createMethods($object);
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

        if ($station->object) {
            DB::transaction(function () use ($station) {
                $station->object->delete();
                $station->delete();
            });
        }

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

    /**
     * Создание методов для станции
     *
     * @param HomeObject $object
     * @return void
     */
    private function createMethods(HomeObject $object): void
    {
        $methods = [
            [
                'name' => 'Запустить команду "Сказать"',
                'comment' => 'Передать сообщение станции. Станция произнесет полученное сообщение',
                'params' => 'Сообщение (Это сообщение отправится станции)',
                'is_system' => 1,
                'script' => $this->getScriptIdByLink('yandex_station_say.php'),
            ],
            [
                'name' => 'Запустить команду "CMD"',
                'comment' => 'Передать сообщение станции. Станция обработает сообщение и даст ответ на него',
                'params' => 'Сообщение (Это сообщение отправится станции)',
                'is_system' => 1,
                'script' => $this->getScriptIdByLink('yandex_station_cmd.php'),
            ]
        ];

        foreach ($methods as $method) {
            $object->methods()->updateOrCreate(['name' => $method['name']], $method);
        }
    }

    /**
     * Поиск скрипта, если не находится, то создаем
     *
     * @param string $link
     * @return int
     */
    private function getScriptIdByLink(string $link): int
    {
        $scripts = ScriptsTableSeeder::getYandexStationScripts();

        foreach ($scripts as $scriptData) {
            Script::updateOrCreate(['link' => $scriptData['link']], $scriptData);
        }

        $script = Script::where('link', $link)->first();

        return $script->id;
    }
}