<?php

namespace App\Http\Controllers;

use App\Http\Requests\Curtain\CurtainFormRequest;
use App\Models\Curtain;
use App\Repositories\CurtainRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ModbusRepository;
use App\Services\CurtainService;
use App\Services\PortService;
use App\Services\Service;

class CurtainController extends Controller
{
    public function __construct(
        private CurtainRepository $curtainRepository,
        private PortService $portService,
        private CurtainService $curtainService,
        private DeviceRepository $deviceRepository,
        private ModbusRepository $modbusRepository,
    ) {
    }

    public function index()
    {
        $curtains = $this->curtainRepository->getAll();

        return view('curtains.index', compact('curtains'));
    }

    public function edit(Curtain $curtain)
    {
        $can = gates('devices.show-object');
        $tab = request()->input('tab') ?? 1;
        $ports = [];
        $idDevice = null;

        if ($curtain->place == Curtain::PLACE_PORT || $curtain->place == Curtain::PLACE_PHASE) {
            [$idDevice, , , $ports] = $this->portService->getCurrentDevPort($curtain->id_object, 'OUT');
        }

        [$messages, $events, $sounds, $views, $rooms, $scripts, , , $alice] = Service::getListElements($curtain->id_object);

        $types = Curtain::getTypes(true);
        $vendors = Curtain::getVendors(true);
        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';
        $availableEvents = Curtain::getEvents();
        $properties = Curtain::getProperties();
        $devices = $this->deviceRepository->getAllToArray();
        $buses = $this->modbusRepository->getAllBusesToArray();
        $allEvents = '';
        $addressAttributes = [];
        $groupAttributes = [];

        if ($curtain->place == Curtain::PLACE_RS485) {
            switch ($curtain->vendor) {
                case 'aok':
                    $addressAttributes = ['min' => 1, 'max' => 99];
                    $groupAttributes = ['min' => 1, 'max' => 16];
                    break;
                case 'onviz':
                    $addressAttributes = ['min' => 1, 'max' => 253];
                    $groupAttributes = ['min' => 1, 'max' => 253];
                    break;
                default:
                    $addressAttributes = ['min' => 0, 'max' => 255];
                    $groupAttributes = ['min' => 0, 'max' => 255];
                    break;
            }
        }

        return view('curtains.edit', compact(
            'types', 'curtain', 'events', 'sounds', 'views', 'rooms', 'addressAttributes',
            'idDevice', 'devices', 'ports', 'messagePoint', 'messages', 'alice', 'tab', 'availableEvents',
            'properties', 'scripts', 'allEvents', 'can', 'buses', 'vendors', 'groupAttributes'
        ));
    }

    public function update(CurtainFormRequest $r, int $id)
    {
        $curtain = Curtain::findOrFail($id);

        try {
            if ($this->curtainService->update($curtain, $r->except('_token'))) {
                return redirect()->route('curtains.edit', [$curtain->id])
                    ->with('success', 'Штора успешно изменена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении шторы '.$curtain->id
                .' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении шторы');
    }

    public function create()
    {
        $types = Curtain::getTypes(true);
        $places = Curtain::getPlaces(true);
        $vendors = Curtain::getVendors(true);
        $devices = $this->deviceRepository->getAllToArray();
        $buses = $this->modbusRepository->getAllBusesToArray();
        $tab = 1;

        return view('curtains.create', compact('types', 'places', 'tab', 'devices', 'buses', 'vendors'));
    }

    public function store(CurtainFormRequest $r)
    {
        try {
            if ($id = $this->curtainService->store($r->except('_token'))) {
                return redirect()->route('curtains.edit', [$id])
                    ->with('success', 'Штора успешно добавлена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении шторы '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении шторы');
    }
}
