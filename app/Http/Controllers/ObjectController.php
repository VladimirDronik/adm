<?php

namespace App\Http\Controllers;

use App\Models\HomeObject;
use Illuminate\Http\Request;
use App\Services\ObjectService;
use Illuminate\Support\Facades\Log;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Http\Requests\Object\CreateRequest;
use App\Http\Requests\Object\UpdateRequest;

class ObjectController extends Controller
{
    public function __construct(
        private ObjectRepository $objectRep,
        private ScriptRepository $scriptRep,
        private ObjectService $service
    ) {
    }

    public function index(Request $r)
    {
        $filter_name = $r->input('name', '');
        $objects = $this->objectRep->getByName($filter_name);

        return view('objects.index', compact('objects', 'filter_name'));
    }

    public function create()
    {
        $types = HomeObject::getFullTypeIds();

        return view('objects.create', compact('types'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('objects.edit', [$id])
                    ->with('success', 'Объект успешно добавлен. Теперь к объекту можно добавить методы');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении объекта '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении объекта');
    }

    public function edit(int $id)
    {
        $object = HomeObject::findOrFail($id);

        $types = HomeObject::getFullTypeIds();
        $scripts = $this->scriptRep->getAllToArray();

        return view('objects.edit', compact(
            'object', 'types', 'scripts'
        ));
    }

    public function update(UpdateRequest $r, int $id)
    {
        $object = HomeObject::findOrFail($id);

        try {
            if ($this->service->update($object, $r->except('_token'))) {
                return redirect()
                    ->route('objects.edit', [$object->id])
                    ->with('success', 'Объект успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении объекта '.$object->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении объекта');
    }
}
