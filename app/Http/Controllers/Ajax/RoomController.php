<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Room;

class RoomController extends Controller
{
    public function addRoom()
    {
        $room = new Room();

        $room->nameRoom = $_POST['name'];
        $room->imageRoom = $_POST['image'];
        $room->colorRoom = $_POST['color'];
        $room->addRoom();

    }

    public function deleteRoom()
    {
        Room::deleteRoom($_POST['id']);
    }

    /**
     * Перемещение строки вниз
     */
    public function sort()
    {
        Room::sort($_POST['id'], $_POST['sort'], $_POST['direction']);
    }

    /**
     * Сохранение названия помещения
     */
    public function saveNameRoom()
    {
        $room = new Room($_POST['idSelectRoom']);
        $room->nameRoom = $_POST['nameRoom'];
        $room->saveName();

        return response()->json(array('success' => true, 'html'=>$_POST['nameRoom']));
    }

    /**
     * Сохраненеие изображения помещения
     */
    public function updateImage()
    {

        $room = new Room($_POST['id']);
        $room->imageRoom = $_POST['image'];
        $room->saveImage();
    }

    /**
     * Сохранение цвета для помещения
     */
    public function updateColor()
    {
        $room = new Room($_POST['id']);
        $room->colorRoom = $_POST['color'];
        $room->saveColor();
    }
}
