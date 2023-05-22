<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppClientInfo\UpdateRequest;
use App\Models\ClientInfo;
use App\Services\ClientAppInfoService;

class ClientInfoController extends Controller
{
    private $service;

    public function __construct(ClientAppInfoService $service)
    {
        $this->service = $service;
    }

    public function edit()
    {
        $clientInfo = ClientInfo::getInfo();
        if (!$clientInfo) {
            $clientInfo = ClientInfo::create();
        }
        $adminAppV = $this->service->getAdminVersion();
        $coreV = $this->service->getCoreVersion();

        return view('app_client_info.edit', compact('clientInfo', 'adminAppV', 'coreV'));
    }

    public function update(UpdateRequest $r)
    {
        try {
            $this->service->update(ClientInfo::getInfo(), $r->except('_token'));

            return redirect()->route('app-client.info.edit')->with('success', 'Данные успешно обновлены');
        } catch (\Throwable $e) {
            \Log::error('Ошибка при обновлении данных клиента', [$e->getMessage()]);
        }
        return redirect()->route('app-client.info.edit')->with('error','Ошибка при сохранении изменений данных клиента');
    }
}
