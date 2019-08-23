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

        $room->nameRoom = $_POST['name'];
        $room->imageRoom = $_POST['image'];
        $room->colorRoom = $_POST['color'];
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


    /**
     * Сохранение названия помещения
     */
    public function saveNameRoom()
    {
        $room = new Rooms($_POST['idSelectRoom']);
        $room->nameRoom = $_POST['nameRoom'];
        $room->saveName();

        return response()->json(array('success' => true, 'html'=>$_POST['nameRoom']));
    }


    /**
     * Сохраненеие изображения помещения
     */
    public function updateImage()
    {

        $room = new Rooms($_POST['id']);
        $room->imageRoom = $_POST['image'];
        $room->saveImage();
    }

    /**
     * Сохранение цвета для помещения
     */
    public function updateColor()
    {
        $room = new Rooms($_POST['id']);
        $room->colorRoom = $_POST['color'];
        $room->saveColor();
    }




}
