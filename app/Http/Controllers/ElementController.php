<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 07.04.21
 * Time: 16:35
 */

namespace App\Http\Controllers;
use App\Models\Elements;
use App\Repositories\ElementRepository;
use App\Services\ImageService;
use App\Services\ElementService;
use App\Http\Requests\Element\CreateRequest;
use App\Http\Requests\Element\UpdateRequest;
use App\Repositories\ObjectRepository;
use App\Services\ObjectService;

class ElementController extends Controller
{
    private $elementRepository;
    private $service;
    private $objectRepository;
    private $objectService;

    public function __construct(ElementRepository $elementRepository, ElementService $service,
                                ObjectRepository $objectRepository, ObjectService $objectService)
    {
        $this->elementRepository = $elementRepository;
        $this->service = $service;
        $this->objectRepository = $objectRepository;
        $this->objectService = $objectService;
    }

    public function create($pageId){

        $types = Elements::getTypes(true);
        $parents = $this->elementRepository->getParentsToArray($pageId);
        $images = ImageService::getMainImages();
        $objects = $objects = $this->objectRepository->getAllToArray();
        $settings = false;


        return view('elements.create', compact('types', 'parents', 'pageId',
                                               'images', 'objects', 'settings'));

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
        $images = ImageService::getMainImages();
        $objects = $this->objectRepository->getAllToArray();
        $element->value = $this->elementRepository->parser($element->value, 'status');
        $settings = $this->elementRepository->parser($element->value, 'settings');
        $settings = filter_var($settings, FILTER_VALIDATE_BOOLEAN);
        $handles = $this->objectService->getPropertiesByObjectId($element->id_object, false);

        return view('elements.edit', compact('element', 'types', 'parents',
                    'objects', 'images', 'handles', 'settings'));
    }

    public function update(UpdateRequest $r, Elements $element)
    {
        try {
            if ($this->service->update($element, $r->except('_token'))) {
                return redirect()->route('elements.edit',[$element->id])->with('success','Настройки успешно изменены');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении настроек'.$element->id.' '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении настроек помещения');
    }
}
