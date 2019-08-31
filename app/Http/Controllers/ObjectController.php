<?php

namespace App\Http\Controllers;

use App\Http\Requests\Object\CreateRequest;
use App\Http\Requests\Object\UpdateRequest;
use App\Models\HomeObject;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\ViewRepository;
use App\Services\ObjectService;
use Illuminate\Http\Request;

class ObjectController extends Controller
{
    private $object_rep;
    private $view_rep;
    private $service;

    public function __construct(ObjectRepository $object_rep, ViewRepository $view_rep, ObjectService $service)
    {
        $this->object_rep = $object_rep;
        $this->view_rep = $view_rep;
        $this->service = $service;
    }

    public function index(Request $r)
    {
        $filter_name = $r->input('name', '');
        $objects = $this->object_rep->getByName($filter_name);

        return view('objects.index', compact('objects','filter_name'));
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
                return redirect()->route('objects.edit',[$id])->with('success', 'Объект успешно добавлен. Теперь к объекту можно добавить методы');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении объекта '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении объекта');
    }

    public function edit(int $id, ScriptRepository $script_rep)
    {
        $object = HomeObject::findOrFail($id);

        $types = HomeObject::getFullTypeIds();
        $views = $this->view_rep->getAllToArray();
        $scripts = $script_rep->getAllToArray();

        return view('objects.edit', compact('object', 'types', 'views', 'scripts'));
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
