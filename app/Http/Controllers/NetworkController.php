<?php

namespace App\Http\Controllers;

use App\Http\Requests\Network\UpdateRequest;
use App\Services\NetworkService;

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
            $service->setIface($r->main_ip, $r->main_mask, $r->main_gateway);
            $service->setIface($r->ip, $r->mask);

            $service->reload($r->main_ip, $r->ip);

            return redirect()->route('network.edit')->with('success', 'Данные успешно обновлены');
        } catch (\Throwable $e) {
            \Log::error('Ошибка при обновлении данных Настроек сети', [$e->getMessage()]);
            if ($e->getCode() == 62) {
                return redirect()
                    ->route('network.edit')
                    ->with('error','Что-то пошло не так. Попробуйте снова. Если ошибка повторится, обратитесь в службу поддержки.');
            }
            if ($e->getCode() == 255) {
                return redirect()
                    ->route('network.edit')
                    ->with('error','Настройки сети не были применены. Попробуйте снова. Если ошибка повторится, обратитесь в службу поддержки.');
            }
        }
        return redirect()->route('network.edit')->with('error','Ошибка при сохранении изменений');
    }
}
