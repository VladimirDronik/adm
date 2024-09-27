<?php

namespace App\Http\Controllers;

use App\Repositories\ObjectRepository;

class EngineeringController extends Controller
{
    public function __construct(
        private ObjectRepository $objectRepository
    ) {
    }

    public function index()
    {
        $equipments = $this->objectRepository->getAllEngineering();

        return view('engineering.index', compact('equipments'));
    }
}
