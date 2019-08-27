<?php

namespace App\Http\Controllers;

use App\Http\Requests\Object\CreateRequest;
use App\Http\Requests\Object\UpdateRequest;
use App\Models\HomeObject;
use App\Repositories\TermostatRepository;
use App\Services\TermostatService;
use Illuminate\Http\Request;

class TermostatController extends Controller
{
    private $termostat_rep;
    private $service;

    public function __construct(TermostatRepository $termostat_rep, TermostatService $service)
    {
        $this->termostat_rep = $termostat_rep;
        $this->service = $service;
    }

    public function index()
    {
        $termostats = $this->termostat_rep->getAll();

        return view('termostats.index', compact('termostats'));
    }

    public function create()
    {
        $types = HomeObject::getFullTypeIds();
        $views = $this->view_rep->getAllToArray();

        return view('objects.create', compact('types', 'views'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('objects.edit',[$id])->with('success', 'Объект успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении объекта '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении объекта');
    }

    public function edit(int $id)
    {
        $object = HomeObject::findOrFail($id);

        $types = HomeObject::getFullTypeIds();
        $views = $this->view_rep->getAllToArray();

        return view('objects.edit', compact('object', 'types', 'views'));
    }

    public function update(UpdateRequest $r, int $id)
    {
        $object = HomeObject::findOrFail($id);

        try {
            if ($this->service->update($object, $r->except('_token'))) {
                return redirect()->route('objects.edit',[$object->id])->with('success','Объект успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении объекта '.$object->id.' '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении объекта');
    }
}
