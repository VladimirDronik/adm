<?php

namespace App\Http\Controllers;

use App\Http\Requests\View\CreateRequest;
use App\Http\Requests\View\UpdateRequest;
use App\Models\View;
use App\Repositories\ColorRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Repositories\SceneRepository;
use App\Repositories\ViewRepository;
use App\Services\ColorService;
use App\Services\ImageService;
use App\Services\ObjectService;
use App\Services\ViewService;
use Illuminate\Http\Request;
use App\Repositories\PageRepository;

class ViewController extends Controller
{
    private $view_rep;
    private $room_rep;
    private $scene_rep;
    private $object_rep;
    private $service;
    private $pages_rep;

    public function __construct(ViewRepository $view_rep, RoomRepository $room_rep, SceneRepository $scene_rep,
                                ViewService $service, ObjectRepository $object_rep, PageRepository $pageRepository)
    {
        $this->view_rep = $view_rep;
        $this->room_rep = $room_rep;
        $this->scene_rep = $scene_rep;
        $this->object_rep = $object_rep;
        $this->service = $service;
        $this->pages_rep = $pageRepository;
    }

    public function getLists()
    {
        $types = View::getFullTypeIds();
        $rooms = $this->room_rep->getAllToArray();
        $scenes = $this->scene_rep->getAll()->pluck('label', 'id')->toArray();
        $images = ImageService::getViewImages();
        $objects = $this->object_rep->getAllToArray();
        $links = $this->pages_rep->getAllToArray();

        return [$types, $rooms, $objects, $scenes, $images, $links];
    }

    public function index(Request $r)
    {
        $views = $this->view_rep->getByRoom($r->room);
        $rooms = $this->room_rep->getSpecialRooms();

        $filter_room = $r->input('room', '');
        $filter_room_name = $this->room_rep->getRoomName($filter_room, $rooms);

        return view('views.index', compact('views', 'rooms', 'filter_room', 'filter_room_name'));
    }

    public function create()
    {
        list($types, $rooms, $objects, $scenes, $images, $links) = $this->getLists();

        $colors = ColorRepository::getColors();

        return view('views.create', compact('types', 'rooms', 'objects', 'scenes', 'images', 'links', 'colors'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('views.edit',[$id])->with('success', 'Отображение успешно добавлено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении отображения ', [$r->all(), $e->getMessage()]);
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении отображения');
    }

    public function edit(View $view, ObjectService $object_service)
    {
        list($types, $rooms, $objects, $scenes, $images, $links) = $this->getLists();
        $methods = $object_service->getMethodsByObjectIdToArray($view->id_object);

        $colors = ColorRepository::getColors();

        $enabletermostat = null;
        $lowval_termostat = null;
        $highval_termostat = null;
        $pushlabel = null;
        $modallabel = null;
        $label_longclick_text = null;

        if($view->type == 'termostat') {
            $onmethodparams = explode(';',$view->params);
            $enabletermostat = explode('=',$onmethodparams[0])[1];
            $lowval_termostat = explode('=',$onmethodparams[1])[1];
            $highval_termostat = explode('=',$onmethodparams[2])[1];
        } elseif ($view->type == 'label') {
            $onmethodparams = explode('&',$view->params);
            $pushlabel = explode('=',$onmethodparams[0])[1];
            $modallabel = explode('=',$onmethodparams[1])[1];
            $label_longclick_text = explode('=',$onmethodparams[2])[1];
        }



        return view('views.edit', compact('view', 'types',
            'rooms', 'methods', 'objects', 'scenes', 'images', 'links', 'colors',
            'enabletermostat', 'lowval_termostat', 'highval_termostat', 'pushlabel', 'modallabel', 'label_longclick_text'));
    }

    public function update(UpdateRequest $r, View $view)
    {
        try {
            if ($this->service->update($view, $r->except('_token'))) {
                return redirect()->route('views.edit',[$view->id])
                    ->with('success','Отображение успешно изменено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении отображения '.$view->id.' '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении отображения');
    }
}
