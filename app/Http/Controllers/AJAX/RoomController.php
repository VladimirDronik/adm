<?php

namespace App\Http\Controllers\AJAX;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rooms;

class RoomController extends Controller
{

    public function addRoom()
    {
        $room = new Rooms();

        $room->name = $_POST['name'];
        $room->image = $_POST['image'];
        $room->color = $_POST['color'];
        $room->addRoom();

    }


    public function deleteRoom()
    {
        Rooms::deleteRoom($_POST['id']);
    }

    /**
     * Перемещение строки вниз
     */
    public function sort()
    {
        Rooms::sort($_POST['id'], $_POST['sort'], $_POST['direction']);
    }


}
