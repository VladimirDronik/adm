<?php

namespace App\Http\Controllers;

use App\Http\Requests\Script\CreateRequest;
use App\Repositories\ScriptRepository;
use App\Services\ScriptService;
use Illuminate\Http\Request;

class ScriptController extends Controller
{
    private $script_rep;
    private $service;

    public function __construct(ScriptRepository $script_rep, ScriptService $service)
    {
        $this->script_rep = $script_rep;
        $this->service = $service;
    }

    public function index(Request $r)
    {
        $filter_name = $r->input('name', '');
        $scripts = $this->script_rep->getByName($filter_name);

        return view('scripts.index', compact('scripts','filter_name'));
    }

    public function create()
    {
        return view('scripts.create');
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('scripts.edit',[$id])->with('success', 'Скрипт успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении скрипта '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении скрипта');
    }

    public function edit(int $id, ScriptRepository $script_rep)
    {
        $object = HomeObject::findOrFail($id);

        $types = HomeObject::getFullTypeIds();
        $scripts = $script_rep->getAllToArray();

        return view('objects.edit', compact('object', 'types', 'scripts'));
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