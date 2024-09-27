<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Repositories\SettingRepository;
use App\Http\Requests\Setting\CreateRequest;
use App\Http\Requests\Setting\UpdateTimeZoneRequest;

class TimeZoneSettingController extends Controller
{
    public function __construct(
        private SettingRepository $settingRep,
        private SettingService $service
    ) {
    }

    public function create()
    {
        $timeZones = $this->getTimeZoneList();

        return view('settings.time_zone.create', compact('timeZones'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('time_zone.edit', [$id])
                    ->with('success', 'Параметр часового пояса успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении параметра часового пояса'
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении параметра часового пояса');
    }

    public function edit(int $id)
    {
        $timeZones = $this->getTimeZoneList();
        $setting = $this->settingRep->getById($id);

        return view('settings.time_zone.edit', compact('setting', 'timeZones'));
    }

    public function update(UpdateTimeZoneRequest $r, int $id)
    {
        $setting = $this->settingRep->getById($id);

        try {
            if ($this->service->update($setting, $r->except('_token'))) {
                return redirect()
                    ->route('time_zone.edit', [$setting->id])
                    ->with('success', 'Параметр часового пояса успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении параметра часового пояса '.$setting->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении параметра часового пояса');
    }

    private static function getTimeZoneList()
    {
        return Cache::rememberForever('timezones_list', function () {
            $timestamp = time();
            foreach (timezone_identifiers_list(\DateTimeZone::ALL) as $key => $value) {
                date_default_timezone_set($value);
                $timezone[$value] = '(UTC '.date('P', $timestamp).') '.str_replace('/', ', ', $value);
            }

            return $timezone;
        });
    }
}
