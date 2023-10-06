<?php

namespace App\Http\Controllers;

use App\Http\Requests\DryContact\CreateRequest;
use App\Http\Requests\DryContact\UpdateRequest;
use App\Models\Drycontact;
use App\Models\HomeObject;
use App\Repositories\DeviceRepository;
use App\Repositories\DrycontactRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Services\DrycontactService;
use App\Services\MessageService;
use App\Services\ObjectService;
use App\Services\PortService;
use App\Services\Service;

class DrycontactController extends Controller
{
    public function __construct(
        private DrycontactRepository $drycontact_rep,
        private DeviceRepository $device_rep,
        private ObjectRepository $object_rep,
        private DrycontactService $service,
        private PortService $portService,
        private ScriptRepository $script_rep,
        private MessageService $messagesService,
        private ObjectService $objectService,
    ) {
    }

    public function index()
    {
        $drycontacts = $this->drycontact_rep->getAll();

        return view('drycontacts.index', compact('drycontacts'));
    }

    public function create()
    {

        $objects = $this->object_rep->getAllToArray();
        $object_types = HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);

        return view('drycontacts.create', compact('objects', 'object_types', 'devices'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('drycontacts.edit', [$id])
                    ->with('success', 'Сухой контакт успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении сухого контакта '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении сухого контакта');
    }

    public function edit(int $id, $tab = 1)
    {
        $drycontact = Drycontact::findOrFail($id);

        [$idDevice, $idPort, $devices, $ports] = $this->portService->getCurrentDevPort($drycontact->id_object,
            'IN,I2C,1WIRE,1W-BUS');

        $method_on = $drycontact->method_on;
        $object_on = $this->objectService->getObjectByMethod($method_on);
        $methods_on = $this->objectService->getMethodsByObjectIdToArray($object_on);

        $method_off = $drycontact->method_off;
        $object_off = $this->objectService->getObjectByMethod($method_off);
        $methods_off = $this->objectService->getMethodsByObjectIdToArray($object_off);

        $messagePoint['first'] = 'При замыкании';
        $messagePoint['second'] = 'При размыкании';

        $can = gates('devices.show-object');

        [$messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice, $allEvents] =
            Service::getListElements($drycontact->id_object);

        $availableEvents = Drycontact::getEvents();
        $properties = Drycontact::getProperties();

        return view('drycontacts.edit', compact('drycontact', 'messagePoint', 'tab',
            'method_on', 'object_on', 'method_off', 'object_off', 'methods_on', 'methods_off',
            'idDevice', 'idPort', 'devices', 'ports', 'events', 'sounds', 'views', 'rooms', 'allEvents',
            'objects', 'object_types', 'scripts', 'messages', 'availableEvents', 'properties', 'can'));
    }

    public function update(UpdateRequest $r, int $id)
    {
        $drycontact = Drycontact::findOrFail($id);

        try {
            if ($this->service->update($drycontact, $r->except('_token'))) {
                return redirect()->route('drycontacts.edit', [$drycontact->id])
                    ->with('success', 'Сухой контакт успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении сухого контакта '.$drycontact->id
                .' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении сухого контакта');
    }
}
