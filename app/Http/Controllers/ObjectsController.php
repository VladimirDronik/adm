<?php

namespace App\Http\Controllers;

use App\Models\HomeObject as Obj;
use Illuminate\Http\Request;

class ObjectsController extends Controller
{

    public function index()
    {
        $objects = Obj::all();
        return view('objects', ['objects' => $objects]);
    }
}
