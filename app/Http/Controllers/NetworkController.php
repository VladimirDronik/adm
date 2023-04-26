<?php

namespace App\Http\Controllers;

use App\Http\Requests\Network\UpdateRequest;
use App\Models\Setting;
use App\Services\NetworkService;
use App\Services\SettingService;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function edit(NetworkService $service)
    {
        try {
            $main_network = $service->getIface(true);
            $network = $service->getIface();
        } catch (\Throwable $e) {
            \Log::error('Ошибка чтения данных для Настроек сети ', [$e->getMessage()]);
        }

        return view('network.edit', compact('main_network', 'network'));
    }

    public function update(UpdateRequest $r, NetworkService $service)
    {
        try {
            $service->setIface($r->ip, $r->mask);
            $service->setIface($r->main_ip, $r->main_mask, $r->main_gateway);

            $service->reload();
            return redirect()->route('network.edit')->with('success', 'Данные успешно обновлены');
        } catch (\Throwable $e) {
            \Log::error('Ошибка при обновлении данных Настроек сети', [$e->getMessage()]);
        }
        return redirect()->route('network.edit')->with('error','Ошибка при сохранении изменений');
    }
}