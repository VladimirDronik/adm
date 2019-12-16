<?php

namespace App\Http\Controllers;

use App\Http\Requests\Script\CreateRequest;
use App\Http\Requests\Script\UpdateRequest;
use App\Models\Script;
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
                return redirect()->route('scripts.index')->with('success', 'Скрипт успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении скрипта '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении скрипта');
    }

    public function edit(int $id)
    {
        if ($script = Script::find($id)) {
            return view('scripts.edit', compact('script'));
        }

        return redirect()->route('scripts.index');
    }

    public function update(UpdateRequest $r, int $id)
    {
        $script = Script::findOrFail($id);

        if (count($script->systemMethods)) {
            return redirect()->route('scripts.edit', [$script->id])->with('error','Редактирование скрипта запрещено');
        }

        try {
            if ($this->service->update($script, $r->except('_token'))) {
                return redirect()->route('scripts.edit',[$script->id])
                    ->with('success','Скрипт успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении скрипта '.$script->id.' '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении скрипта');
    }
}