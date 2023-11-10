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
        $cameras = $this->cctvRep->getAllCameras();
        $recorders = $this->cctvRep->getAllRecorders();

        $allRecordersCameras = $this->cctvRep->getAllRecordersCameras();

        if ($allRecordersCameras->isNotEmpty()) {
            foreach ($allRecordersCameras as $recorderCamera) {
                $recorder = $recorderCamera->recorder;

                $link = str_replace(
                    ['$login', '$password', '$ip_address'],
                    [$recorder->login, customDecrypt($recorder->password, config('secret.password_key')), $recorder->ip_address],
                    $recorderCamera->link
                );

                if ($link) {
                    try {
                        Http::post('http://localhost:9997/v3/config/paths/add/camera' . $recorderCamera->id, [
                            'source' => $link,
                        ]);
                    } catch (\Throwable $th) {
                    }
                }
            }
        }

        return view('cctv.index', compact('cameras', 'recorders', 'tab'));
    }
}
