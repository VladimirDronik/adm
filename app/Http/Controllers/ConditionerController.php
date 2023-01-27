<?php

namespace App\Http\Controllers;



use App\Repositories\ConditionerRepository;
use App\Repositories\RoomRepository;

class ConditionerController extends Controller
{
    private $conditionersRep;


    public function __construct(ConditionerRepository $conditionersRep)
    {
        $this->conditionersRep = $conditionersRep;
    }


    public function index()
    {
        $conditioners = $this->conditionersRep->getAll();

        return view('conditioners.index', compact('conditioners'));
    }

    public function edit()
    {

    }

    public function create()
    {
        $vendors = $this->conditionersRep->getAllVendorsToArray();
        $models = $this->conditionersRep->getModelsByVendorToArray(1);
        $room = [1,2,3];

        return view('conditioners.create', compact('vendors', 'models', 'room'));
    }


}