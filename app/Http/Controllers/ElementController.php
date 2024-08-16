<?php

namespace App\Http\Controllers;

use App\Models\Elements;
use App\Services\ObjectService;
use App\Services\ElementService;
use App\Repositories\ObjectRepository;
use App\Repositories\ElementRepository;
use App\Http\Requests\Element\CreateRequest;
use App\Http\Requests\Element\UpdateRequest;

class ElementController extends Controller
{
    public function __construct(
        private ElementRepository $elementRepository,
        private ElementService $service,
        private ObjectRepository $objectRepository,
        private ObjectService $objectService
    ) {
    }

    public function create($pageId)
    {
        $types = Elements::getTypes(true);
        $parents = $this->elementRepository->getParentsToArray($pageId);
        $objects = $objects = $this->objectRepository->getAllToArray();
        $settings = false;

        return view('elements.create', compact('types', 'parents', 'pageId', 'objects', 'settings'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($idPage = $this->service->store($r->except('_token'))) {
                return redirect()->route('pages.edit', [$idPage])
                    ->with('success', 'Элемент успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении элемента '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении элемента');
    }

    public function edit(Elements $element)
    {
        $types = Elements::getTypes(true);
        $parents = $this->elementRepository->getParentsToArray($element->page);
        $objects = $this->objectRepository->getAllToArray();
        $settings = $this->elementRepository->parser($element->value, 'settings');
        $settings = filter_var($settings, FILTER_VALIDATE_BOOLEAN);
        $element->value = $this->elementRepository->parser($element->value, 'status');
        $handles = $this->objectService->getPropertiesByObjectId($element->id_object, false);

        return view('elements.edit', compact('element', 'types', 'parents', 'objects', 'handles', 'settings'));
    }

    public function update(UpdateRequest $r, Elements $element)
    {
        try {
            if ($this->service->update($element, $r->except('_token'))) {
                return redirect()->route('elements.edit', [$element->id])->with('success', 'Элемент успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении элемента '.$element->id.' '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении элемента');
    }
}
