<?php

namespace App\Http\Controllers;

use App\Rooms;
use App\View;
use Illuminate\Http\Request;

class ViewsController extends Controller
{
    public function index()
    {

        $views = View::all();
        $rooms = Rooms::getAllRooms();

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
