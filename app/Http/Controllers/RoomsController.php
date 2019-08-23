<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomsController extends Controller
{

    public function index()
    {
        $rooms = Room::getAllRooms();
        $colors = Room::getAllColors();
        $images = Room::getAllImages();

        return view('rooms', ['rooms' => $rooms, 'colors' => $colors, 'images' => $images]);
    }


}
