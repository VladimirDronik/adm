<?php

namespace App\Http\Controllers;

use App\Object;
use Illuminate\Http\Request;

class ObjectsController extends Controller
{

    public function index()
    {
        $objects = Object::all();
        return view('objects', ['objects' => $objects]);
    }
}
