<?php

namespace App\Http\Controllers;

use App\Rooms;
use App\View;
use Illuminate\Http\Request;

class ViewsController extends Controller
{
    private $views;
    private $currentRoom = '';

    public function index()
    {
        $this->views = View::all();
        return $this->returnView();
    }


    /**
     * Выводит представления при выборе помещения в фильтре
     *
     * @param $idRoom
     *
     * @return view
     */
    public function getFilteredViews($idRoom)
    {
        $this->views = View::getViews($idRoom);
        $this->currentRoom = Rooms::nameRoomFromId($idRoom);
        return $this->returnView();
    }



    public function returnView()
    {

        $rooms = Rooms::getAllRooms();

        return view('views', ['views' => $this->views, 'rooms' => $rooms, 'currentRoom' => $this->currentRoom]);
    }

}
