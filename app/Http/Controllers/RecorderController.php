<?php

namespace App\Http\Controllers;

use App\Models\Recorder;
use App\Services\RecorderService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Recorder\CreateRequest;
use App\Http\Requests\Recorder\UpdateRequest;

class RecorderController extends Controller
{
    public function __construct(
        private RecorderService $service
    ) {
    }

    public function edit(Recorder $recorder)
    {
        return view('cctv.recorder.edit', compact('recorder'));
    }

    public function update(UpdateRequest $r, Recorder $recorder)
    {
        try {
            if ($this->service->update($recorder, $r->except('_token'))) {
                return redirect()
                    ->route('recorders.edit', [$recorder->id])
                    ->with('success', 'Видеорегистратор успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении видеорегистратора '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении видеорегистратора');
    }

    public function create()
    {
        $vendors = Recorder::getVendors();

        return view('cctv.recorder.create', compact('vendors'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('recorders.edit', [$id])
                    ->with('success', 'Видеорегистратор успешно добавлена');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении видеорегистратора '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении видеорегистратора');
    }
}
