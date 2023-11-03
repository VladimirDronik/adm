<?php

namespace App\Http\Controllers;

use App\Repositories\CctvRepository;
use Illuminate\Support\Facades\Http;

class CctvController extends Controller
{
    public function __construct(
        private CctvRepository $cctvRep
    ) {
    }

    public function index()
    {
        $tab = request()->input('tab') ?? 'cameras';
        $camerasWithoutRecorders = $this->cctvRep->getAllCamerasWithoutRecorder();
        $recorders = $this->cctvRep->getAllRecorders();

        $allRecordersCameras = $this->cctvRep->getAllRecordersCameras();

        if ($allRecordersCameras->isNotEmpty()) {
            foreach ($allRecordersCameras as $recorderCamera) {
                try {
                    Http::post('http://localhost:9997/v2/config/paths/add/camera' . $recorderCamera->id, [
                        'source' => $recorderCamera->link,
                    ]);
                } catch (\Throwable $th) {
                }
            }
        }

        return view('cctv.index', compact('camerasWithoutRecorders', 'recorders', 'tab'));
    }
}
