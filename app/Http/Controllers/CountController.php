<?php

namespace App\Http\Controllers;

use App\Http\Requests\Count\CreateRequest;
use App\Http\Requests\Count\UpdateRequest;
use App\Models\Count;
use App\Models\HomeObject;
use App\Repositories\CountRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Services\CountService;

class CountController extends Controller
{
    private $count_rep;
    private $object_rep;
    private $service;

    public function __construct(CountRepository $count_rep, ObjectRepository $object_rep,
                                CountService $service)
    {
        $this->count_rep = $count_rep;
        $this->object_rep = $object_rep;
        $this->service = $service;
    }

    public function index()
    {
        $counts = $this->count_rep->getAll();

        return view('counts.index', compact('counts'));
    }

    public function create()
    {
        $types = Count::getTypes(true);
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();

        return view('counts.create', compact('types', 'objects', 'object_types'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('counts.edit', [$id])
                    ->with('success', 'Счетчик успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении счетчика ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении счетчика');
    }

    public function edit(Count $count, ScriptRepository $script_rep)
    {
        $types = Count::getTypes(true);
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();

        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');

        return view('counts.edit', compact('count', 'types',
            'objects', 'object_types', 'scripts', 'can'));
    }

    public function update(UpdateRequest $r, Count $count)
    {
        try {
            if ($this->service->update($count, $r->except('_token'))) {
                return redirect()->route('counts.edit',[$count->id])
                    ->with('success', 'Счетчик успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении счетчика '.$count->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении счетчика');
    }
}
