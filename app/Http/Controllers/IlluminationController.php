<?php

namespace App\Http\Controllers;

use App\Repositories\IlluminationRepository;

class IlluminationController extends Controller
{
    public function __construct(
        private IlluminationRepository $illuminationRep,
    ) {
    }

    public function index()
    {
        $illuminations = $this->illuminationRep->getAll();

        return view('illumination.index', compact('illuminations'));
    }
}
