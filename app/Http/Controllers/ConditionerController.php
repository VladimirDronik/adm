<?php

namespace App\Http\Controllers;

use App\Models\HomeObject;
use App\Models\Relay;
use App\Repositories\ConditionerRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Services\ConditionerService;
use Illuminate\Http\Request;

class ConditionerController extends Controller
{
    private $conditionersRep;
    private $objectRep;
    private $deviceRep;
    private $roomRep;
    private $service;

    public function __construct(
        ConditionerRepository $conditionersRep,
        ObjectRepository $objectRep,
        DeviceRepository $deviceRep,
        RoomRepository $roomRep,
        ConditionerService $service
    )
    {
        $this->conditionersRep = $conditionersRep;
        $this->objectRep = $objectRep;
        $this->deviceRep = $deviceRep;
        $this->roomRep = $roomRep;
        $this->service = $service;
    }

    public function index()
    {
        $conditioners = $this->conditionersRep->getAll();

        return view('conditioners.index', compact('conditioners'));
    }

    public function edit()
    {

    }

    public function create()
    {
        $vendors = $this->conditionersRep->getAllVendorsToArray();
        $objects = $this->objectRep->getAllToArray();
        $devices = $this->deviceRep->getAllWithoutTypesToArray(['Hite-pro']);
        $object_types =  HomeObject::getFullTypeIds();
        $room = $this->roomRep->getAllToArray();

        return view('conditioners.create', compact('vendors', 'objects', 'devices', 'object_types', 'room'));
    }

    public function store(Request $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('switches.edit', [$id])
                    ->with('success', 'Кондиционер успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении кондиционера ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении кондиционера');
    }
}
