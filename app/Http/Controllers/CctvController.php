<?php

namespace App\Http\Controllers;

use App\Repositories\CctvRepository;

class CctvController extends Controller
{
    public function __construct(
        private CctvRepository $cctvRep
    ) {
    }

    public function index()
    {
        $tab = request()->input('tab') ?? 'cameras';
        $cameras = $this->cctvRep->getAllCameras();
        $recorders = $this->cctvRep->getAllRecorders();

        return view('cctv.index', compact(
            'cameras', 'recorders', 'tab'
        ));
    }
}
