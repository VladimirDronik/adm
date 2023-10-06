<?php

namespace App\Http\Controllers;

use App\Http\Requests\Room\UpdateRequest;
use App\Models\Color;
use App\Models\Room;
use App\Repositories\RoomRepository;
use App\Services\ColorService;
use App\Services\ImageService;
use App\Services\RoomService;

class RoomController extends Controller
{
    private $room_rep;
    private $service;

    public function __construct(RoomRepository $room_rep, RoomService $service)
    {
        $this->room_rep = $room_rep;
        $this->service = $service;
    }

    public function index()
    {
        $rooms = $this->room_rep->getPaginationGroupsAndSeparateRooms();
        $groups = $this->room_rep->getRoomGroups();
        $colors = ColorService::getAllByType(Color::NAME_TYPE);
        $images = ImageService::getRoomImages();

        return view('rooms.index', compact('rooms', 'groups', 'colors', 'images'));
    }

    public function edit(Room $room)
    {
        if ($room->is_group) {
            return redirect()->route('rooms.index');
        }

        $groups = $this->room_rep->getRoomGroups()->pluck('name', 'id')->toArray();

        return view('rooms.edit_room', compact('room', 'groups'));
    }

    public function update(UpdateRequest $r, Room $room)
    {
        try {
            if ($this->service->update($room, $r->except('_token'))) {
                return redirect()->route('rooms.edit',[$room->id])->with('success','Настройки успешно изменены');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении настроек помещения'.$room->id.' '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении настроек помещения');
    }
}
