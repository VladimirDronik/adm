<?php

namespace App\Http\Controllers;

use App\Models\Drycontact;
use App\Services\DrycontactService;
use Illuminate\Http\Request;
use App\Http\Requests\DryContact\CreateRequest;
use App\Http\Requests\DryContact\UpdateRequest;
use App\Repositories\DrycontactRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Models\HomeObject;
use App\Repositories\ScriptRepository;

class DrycontactController extends Controller
{
    private $drycontact_rep;
    private $object_rep;
    private $device_rep;
    private $service;

    public function __construct(DrycontactRepository $drycontact_rep, DeviceRepository $device_rep,
                                ObjectRepository $object_rep, DrycontactService $service)
    {
        $this->drycontact_rep = $drycontact_rep;
        $this->device_rep = $device_rep;
        $this->object_rep = $object_rep;
        $this->service = $service;
    }

    public function index()
    {
        $drycontacts = $this->drycontact_rep->getAll();

        return view('drycontacts.index', compact('drycontacts'));
    }

    public function create()
    {

        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllToArray();

        return view('drycontacts.create', compact('types', 'objects', 'object_types', 'devices'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('drycontacts.edit', [$id])
                    ->with('success', 'Сухой контакт успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении сухого контакта ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении сухого контакта');
    }


    public function edit(int $id, ScriptRepository $script_rep)
    {
        $drycontact = Drycontact::findOrFail($id);

        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();

        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');

        return view('drycontacts.edit', compact('drycontact',
            'objects', 'object_types', 'scripts', 'can'));
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
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении сухого контакта');
    }
}
