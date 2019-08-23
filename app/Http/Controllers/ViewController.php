<?php

namespace App\Http\Controllers;

use App\Repositories\RoomRepository;
use App\Repositories\ViewRepository;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    private $rep;
    private $room_rep;

    public function __construct(ViewRepository $repository, RoomRepository $room_repository)
    {
        $this->rep = $repository;
        $this->room_rep = $room_repository;
    }

    public function index()
    {
        $views = $this->rep->getAll();
        $rooms = $this->room_rep->getAll();

        return view('views.index', compact('views','rooms'));
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
