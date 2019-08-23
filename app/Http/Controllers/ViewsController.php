<?php

namespace App\Http\Controllers;

use App\Rooms;
use App\View;
use Illuminate\Http\Request;

class ViewsController extends Controller
{
<<<<<<< HEAD
    private $views;
    private $currentRoom = '';

    public function index()
    {
        $this->views = View::all();
        return $this->returnView();
    }

=======
    public function index()
    {

        $views = View::all();
        $rooms = Rooms::getAllRooms();

        return view('views', ['views' => $views, 'rooms' => $rooms]);

    }
>>>>>>> 4d7144ce68cdd8e0e79af2ccb200d1d1a846fd67

    /**
     * Выводит представления при выборе помещения в фильтре
     *
<<<<<<< HEAD
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

=======
     * @param $name
     */
   /*
    public function getFilteredViews($name)
    {
       // echo $name;
    }
   */
>>>>>>> 4d7144ce68cdd8e0e79af2ccb200d1d1a846fd67
}
