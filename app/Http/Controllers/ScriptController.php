<?php

namespace App\Http\Controllers;

use App\Models\Script;
use Illuminate\Http\Request;
use App\Services\ScriptService;
use Illuminate\Support\Facades\Log;
use App\Repositories\ScriptRepository;
use App\Http\Requests\Script\CreateRequest;
use App\Http\Requests\Script\UpdateRequest;

class ScriptController extends Controller
{
    public function __construct(
        private ScriptRepository $scriptRep,
        private ScriptService $service
    ) {
    }

    public function index(Request $r)
    {
        $filter_name = $r->input('name', '');
        $can = gates(['scripts.*-system', 'scripts.edit']);
        $scripts = $this->scriptRep->getByName($filter_name, $can['scripts.show-system']);

        return view('scripts.index', compact('scripts', 'filter_name', 'can'));
    }

    public function create()
    {
        $can = gates(['scripts.*-system', 'scripts.edit']);

        if (! $can['scripts.edit']) {
            return redirect()->route('scripts.index');
        }

        return view('scripts.create', compact('can'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('scripts.index')
                    ->with('success', 'Скрипт успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении скрипта '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении скрипта');
    }

    public function edit(int $id)
    {
        if ($script = Script::find($id)) {
            $can = gates(['scripts.*-system', 'scripts.edit']);

            if ($can['scripts.edit']) {
                return view('scripts.edit', compact('script', 'can'));
            }
        }

        return redirect()->route('scripts.index');
    }

    public function update(UpdateRequest $r, int $id)
    {
        $script = Script::findOrFail($id);

        if (count($script->systemMethods)) {
            return redirect()
                ->route('scripts.edit', [$script->id])
                ->with('error', 'Редактирование скрипта запрещено');
        }

        try {
            if ($this->service->update($script, $r->except('_token'))) {
                return redirect()
                    ->route('scripts.edit', [$script->id])
                    ->with('success', 'Скрипт успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении скрипта '.$script->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении скрипта');
    }
}
