<?php

namespace App\Http\Controllers;

use App\Http\Requests\Network\UpdateRequest;
use App\Services\NetworkService;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function edit(NetworkService $service)
    {
        try {
            $main_network = $service->getIface(true);
            $network = $service->getIface();
            $vpn = $service->getVpn();
        } catch (\Throwable $e) {
            \Log::error('Ошибка чтения данных для Сеть и VPN ', [$e->getMessage()]);
        }

        return view('network.edit', compact('main_network', 'network', 'vpn'));
    }

    public function update(UpdateRequest $r, NetworkService $service)
    {
        try {
            $service->setIface($r->ip, $r->mask);
            $service->setIface($r->main_ip, $r->main_mask, $r->main_gateway);
            $service->setVpn($r->vpn_address, trim($r->vpn_login), $r->vpn_password);
            $service->reload();
            return redirect()->route('network.edit')->with('success', 'Данные успешно обновлены');
        } catch (\Throwable $e) {
            \Log::error('Ошибка при обновлении данных Сеть и VPN',[$e->getMessage()]);
        }
        return redirect()->route('network.edit')->with('error','Ошибка при сохранении изменений');
    }
}