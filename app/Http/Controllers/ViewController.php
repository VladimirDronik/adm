<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\View;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function index()
    {
        $views = View::all();
        $rooms = Room::getAllRooms();

        return view('views', ['views' => $views, 'rooms' => $rooms]);
    }

    /**
     * Выводит представления при выборе помещения в фильтре
     *
     * @param $name
     */
   /*
    public function getFilteredViews($name)
    {
       // echo $name;
    }
   */
}
