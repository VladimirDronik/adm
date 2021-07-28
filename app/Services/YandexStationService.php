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

    public function store(array $data): int
    {

        $station = new YandexStation();
        $this->prepare($station, $data);
        $station->active = 1;

        $station->save();

        $dir = env('SERVER_FOLDER');

        //Выполняем внешний файл инициализации яндексстанции
        passthru("(cd {$dir} && php -f alice_init.php &) >> /dev/null 2>&1");



        return $station->id;
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