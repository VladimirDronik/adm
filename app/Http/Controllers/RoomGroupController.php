<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Repositories\RoomRepository;
use App\Services\ColorService;
use App\Services\ImageService;
use App\Services\RoomService;

class RoomGroupController extends Controller
{
    public function __construct(
        private RoomRepository $room_rep,
        private RoomService $service
    ) {
    }

    public function index(int $id)
    {
        $group = $this->room_rep->getGroup($id);

        if (! $group) {
            return redirect()->route('rooms.index');
        }

        $rooms = $this->room_rep->getPaginationGroupRooms($group->id);
        $groups = $this->room_rep->getRoomGroups();
        $colors = ColorService::getAllByType(Color::NAME_TYPE);
        $images = ImageService::getRoomImages();

        return view('rooms.group_index', compact('group', 'rooms',
            'groups', 'colors', 'images'));
    }
}
