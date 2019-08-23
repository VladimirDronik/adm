<?php

namespace App\Http\Controllers;

use App\Models\HomeObject;
use Illuminate\Http\Request;

class ObjectController extends Controller
{
    public function index()
    {
        $objects = HomeObject::all();
        return view('objects', ['objects' => $objects]);
    }
}
