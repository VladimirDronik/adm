<?php

namespace App\Http\Controllers;

use App\Http\Requests\View\CreateRequest;
use App\Http\Requests\View\UpdateRequest;
use App\Models\View;
use App\Repositories\RoomRepository;
use App\Repositories\SceneRepository;
use App\Repositories\ViewRepository;
use App\Services\ImageService;
use App\Services\ViewService;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    private $view_rep;
    private $room_rep;
    private $scene_rep;
    private $service;

    public function __construct(ViewRepository $view_repository, RoomRepository $room_repository, SceneRepository $scene_rep,
                                ViewService $service)
    {
        $this->view_rep = $view_repository;
        $this->room_rep = $room_repository;
        $this->scene_rep = $scene_rep;

        $this->service = $service;
    }

    public function getLists()
    {
        $types = View::getFullTypeNameIds();
        $rooms = $this->room_rep->getAllToArray();
        $scenes = $this->scene_rep->getAll()->pluck('label', 'id')->toArray();
        $images = ImageService::getViewImages();

        return [$types, $rooms, $scenes, $images];
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
        list($types, $rooms, $scenes, $images) = $this->getLists();

        return view('views.create', compact('types', 'rooms', 'scenes', 'images'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('views.edit',[$id])->with('success', 'Отображение успешно добавлено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении отображения '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении отображения');
    }

    public function edit(View $view)
    {
        list($types, $rooms, $scenes, $images) = $this->getLists();

        return view('views.edit', compact('view', 'types', 'rooms', 'scenes', 'images'));
    }

    public function update(UpdateRequest $r, View $view)
    {
        try {
            if ($this->service->update($view, $r->except('_token'))) {
                return redirect()->route('views.edit',[$view->id])->with('success','Отображение успешно изменено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении отображения '.$view->id.' '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении отображения');
    }
}
