<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\View;
use App\Repositories\RoomRepository;
use App\Repositories\SceneRepository;
use App\Repositories\ViewRepository;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    private $view_rep;
    private $room_rep;
    private $scene_rep;

    public function __construct(ViewRepository $view_repository, RoomRepository $room_repository, SceneRepository $scene_rep)
    {
        $this->view_rep = $view_repository;
        $this->room_rep = $room_repository;
        $this->scene_rep = $scene_rep;
    }

    public function index()
    {
        $views = $this->view_rep->getAll();
        $rooms = $this->room_rep->getAll();

        return view('views.index', compact('views', 'rooms') +
            ['currentRoom' => '']);
    }

    public function create()
    {
        $types = View::getFullTypeIds();
        $rooms = $this->room_rep->getAll()->pluck('name', 'id')->toArray();
        $scenes = $this->scene_rep->getAll()->pluck('label', 'id')->toArray();

        return view('views.create', compact('types', 'rooms', 'scenes'));
    }

    /**
     * Выводит представления при выборе помещения в фильтре
     *
     * @param $name
     */
    public function getFilteredViews($idRoom)
    {
        $views = View::getViews($idRoom);
        $currentRoom = Room::nameRoomFromId($idRoom);
        $rooms = $this->room_rep->getAll();

        return view('views.index', compact('views', 'rooms', 'currentRoom'));
    }

}
