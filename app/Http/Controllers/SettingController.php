<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Support\Facades\Log;
use App\Repositories\SettingRepository;
use App\Http\Requests\Setting\CreateRequest;
use App\Http\Requests\Setting\UpdateRequest;

class SettingController extends Controller
{
    public function __construct(
        private SettingRepository $settingRep,
        private SettingService $service
    ) {
    }

    public function index()
    {
        $settings = $this->settingRep->getAll();

        return view('settings.index', compact('settings'));
    }

    public function create()
    {
        return view('settings.create');
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('settings.edit', [$id])
                    ->with('success', 'Параметр успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении параметра настройки'
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении параметра');
    }

    public function edit(Setting $setting)
    {
        return view('settings.edit', compact('setting'));
    }

    public function update(UpdateRequest $r, Setting $setting)
    {
        try {
            if ($this->service->update($setting, $r->except('_token'))) {
                return redirect()
                    ->route('settings.edit', [$setting->id])
                    ->with('success', 'Параметр успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении параметра настройки '.$setting->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении параметра');
    }
}
