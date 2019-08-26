<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Services\ImageService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::getAllRooms();
        $colors = Room::getAllColors();
        $images = ImageService::getRoomImages();

        return view('rooms', compact('rooms', 'colors', 'images'));
    }
}
