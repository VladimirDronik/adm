<?php

namespace App\Http\Controllers;

use App\Usensor;
use Illuminate\Http\Request;
use App\Models\HomeObject;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Repositories\UsensorRepository;
use App\Services\UsensorService;




class UsensorController extends Controller
{

    private $usensor_rep;
    private $object_rep;
    private $device_rep;
    private $room_rep;
    private $service;

    public function __construct(UsensorRepository $usensor_rep, ObjectRepository $object_rep,
                                DeviceRepository $device_rep, RoomRepository $room_rep, UsensorService $service)
    {
        $this->usensor_rep = $usensor_rep;
        $this->object_rep = $object_rep;
        $this->device_rep = $device_rep;
        $this->room_rep = $room_rep;
        $this->service = $service;
    }

    public function index()
    {
        $usensors = $this->usensor_rep->getAll();

        return view('usensors.index', compact('usensors'));
    }

    private function getLists()
    {
        $objects = $this->object_rep->getAllToArray();
        $rooms = $this->room_rep->getAllToArray();
        $devices = $this->device_rep->getAllToArray();

        return [$objects, $rooms, $devices];
    }

    public function create()
    {
        list($objects, $rooms, $devices) = $this->getLists();
        $object_types =  HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');

        return view('usensors.create', compact('objects','rooms', 'devices', 'object_types', 'can'));
    }
}
