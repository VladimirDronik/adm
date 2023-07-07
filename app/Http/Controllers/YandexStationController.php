<?php

namespace App\Http\Controllers;

use App\Http\Requests\YandexStation\CreateRequest;
use App\Http\Requests\YandexStation\UpdateRequest;
use App\Models\YandexStation;
use App\Repositories\RoomRepository;
use App\Repositories\YandexStationRepository;
use App\Services\YandexIntegration\YandexQuasar;
use App\Services\YandexStationService;
use Illuminate\Http\Request;

class YandexStationController extends Controller
{

    private $yandexstation_rep;
    private $room_rep;
    private $service;

    public function __construct(YandexStationRepository $yandexstationRepository, RoomRepository $roomRepository,
    YandexStationService $yandexStationService)
    {
        $this->yandexstation_rep = $yandexstationRepository;
        $this->room_rep = $roomRepository;
        $this->service = $yandexStationService;
    }

    public function index()
    {
        $yandexstations = $this->yandexstation_rep->getAll();

        return view('yandexstations.index', compact('yandexstations'));
    }


    public function create()
    {
        $rooms = $this->room_rep->getAllToArray();

        return view('yandexstations.create', compact( 'rooms' ));
    }

    public function edit(YandexStation $yandexstation)
    {
        $rooms = $this->room_rep->getAllToArray();

        return view('yandexstations.edit', compact( 'rooms', 'yandexstation' ));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('yandexstations.index')
                    ->with('success', 'Станция успешно добавлена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении станции ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении станции');
    }

    public function update(UpdateRequest $r, YandexStation $yandexstation)
    {
        try {
            if ($this->service->update($yandexstation, $r->except('_token'))) {
                return redirect()->route('yandexstations.edit',[$yandexstation->id])
                    ->with('success', 'Станция успешно изменена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении станции '.$yandexstation->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении станции');
    }

    //Открывает файл cookies.txt для редакатирование
    public function editCookies()
    {
        $dir = env('SERVER_FOLDER');
        $file = '';

        $handle = @fopen($dir."cookies.txt", "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $file = $file.$buffer;
            }
            fclose($handle);
        }

        return view('yandexstations.edit_cookies', compact( 'file' ));

    }

    //Сохраняет изменения в файле cookies.txt
    public function updateCookies(Request $r)
    {

        $dir = env('SERVER_FOLDER');

        //Очищаем файл и записываем в него данные о cookies
        file_put_contents($dir."cookies.txt", '');
        file_put_contents($dir."cookies.txt", $r->file, FILE_APPEND | LOCK_EX);

        return redirect()->route('yandexstations.index')
            ->with('success', 'Файл cookies.txt успешно изменён.');
    }

    /**
     * Авторизация в яндексе и получение станций
     */
    public function yandexAuth(Request $r)
    {
        $yandexSession = new YandexQuasar('artem.s@webest.ru', '********');
        dd($yandexSession->getStations());
    }
}
