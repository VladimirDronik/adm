<?php

namespace App\Http\Controllers;

use App\Repositories\RoomRepository;
use App\Services\ColorService;
use App\Services\ImageService;
use App\Services\RoomService;

class RoomGroupController extends Controller
{
    private $room_rep;
    private $service;

    public function __construct(RoomRepository $room_rep, RoomService $service)
    {
        $this->room_rep = $room_rep;
        $this->service = $service;
    }

    public function index(int $id)
    {
        $group = $this->room_rep->getGroup($id);

        if (!$group) {
            return redirect()->route('rooms.index');
        }

        $rooms = $this->room_rep->getPaginationGroupRooms($group->id);
        $groups = $this->room_rep->getRoomGroups();
        $colors = ColorService::getAll();
        $images = ImageService::getRoomImages();

        return view('rooms.group_index', compact('group', 'rooms',
            'groups', 'colors', 'images'));
    }
}
