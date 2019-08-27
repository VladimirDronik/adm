<?php

namespace App\Http\Controllers;

use App\Repositories\RoomRepository;
use App\Services\ColorService;
use App\Services\ImageService;
use App\Services\RoomService;
use Illuminate\Http\Request;

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
        $rooms = $this->room_rep->getPaginationSpecialRooms();
        $colors = ColorService::getAll();
        $images = ImageService::getRoomImages();

        return view('rooms.index', compact('rooms', 'colors', 'images'));
    }
}
