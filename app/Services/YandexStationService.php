<?php


namespace App\Services;


use App\Models\YandexStation;
use Illuminate\Support\Facades\Storage;

class YandexStationService
{


    private function prepare(YandexStation $station, array $data)
    {

        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }

        $station->fill($data);
    }

    public function store(array $devices): int
    {
        foreach ($devices as $device) {
            $station = YandexStation::where('speaker_id', $device['id'])->first();
            if (!$station) {
                $station = new YandexStation();
            }

            $station->speaker_id = $device['id'];
            $station->name = $device['name'];
            $station->active = 1;

            $station->save();
        }

        // $station = new YandexStation();
        // $this->prepare($station, $data);
        // $station->active = 1;

        // $station->save();

        // $dir = env('SERVER_FOLDER');

        // //Выполняем внешний файл инициализации яндексстанции
        // passthru("(cd {$dir} && php -f alice_init.php &) >> /dev/null 2>&1");



        return 1;
    }

    public function update(YandexStation $station, array $data)
    {
        $this->prepare($station, $data);
        $station->save();

        return $station->id;
    }

    public function delete(int $idStation)
    {
        $station = YandexStation::findOrFail($idStation);
        $station->delete();

        $dir = env('SERVER_FOLDER');

        //Выполняем внешний файл инициализации яндексстанции
        passthru("(cd {$dir} && php -f alice_init.php &) >> /dev/null 2>&1");

        return true;
    }
}