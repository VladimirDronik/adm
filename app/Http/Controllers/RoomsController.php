<?php

namespace App\Http\Controllers;

use App\Colors;
use App\Rooms;
use Illuminate\Http\Request;

class RoomsController extends Controller
{

    public function index()
    {
        $rooms = Rooms::getAllRooms();
        $colors = Rooms::getAllColors();
        $images = Rooms::getAllImages();

        return view('rooms', ['rooms' => $rooms, 'colors' => $colors, 'images' => $images]);
    }


}
