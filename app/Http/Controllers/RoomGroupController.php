<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Services\RoomService;
use App\Services\ColorService;
use App\Services\ImageService;
use App\Repositories\RoomRepository;

class RoomGroupController extends Controller
{
    public function __construct(
        private RoomRepository $roomRep,
        private RoomService $service
    ) {
    }

    public function index(int $id)
    {
        $group = $this->roomRep->getGroup($id);

        if (! $group) {
            return redirect()->route('rooms.index');
        }

        $rooms = $this->roomRep->getPaginationGroupRooms($group->id);
        $groups = $this->roomRep->getRoomGroups();
        $colors = ColorService::getAllByType(Color::NAME_TYPE);
        $images = ImageService::getRoomImages();

        return view('rooms.group_index', compact(
            'group', 'rooms', 'groups', 'colors', 'images'
        ));
    }
}
