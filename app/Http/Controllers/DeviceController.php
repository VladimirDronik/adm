<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use App\Services\DeviceService;
use App\Services\ConfigMegaService;
use Illuminate\Support\Facades\Log;
use App\Repositories\DeviceRepository;
use App\Http\Requests\Device\CreateRequest;
use App\Repositories\ExtensionModuleRepository;

class DeviceController extends Controller
{
    public function __construct(
        private DeviceRepository $deviceRep,
        private DeviceService $service,
        private ConfigMegaService $megaService,
        private ExtensionModuleRepository $extModuleRep
    ) {
    }

    public function index()
    {
        $devices = $this->deviceRep->getByName();

        return view('devices.index', compact('devices'));
    }

    public function create()
    {
        $devtypes = $this->deviceRep->getDevTypesToArray();

        return view('devices.create', compact('devtypes'));
    }

    /**
     * Отправка конфига на выбранный контроллер
     */
    public function sendConfig(int $id, Request $request)
    {
        $error = ConfigMegaService::sendConfigToDevice($id);

        if (! $error) {
            $devices = $this->deviceRep->getByName();

            return view('devices.index', compact('devices'));
        } else {
            return back()->withErrors($error);
        }
    }

    /**
     * Отправка конфига на все доступные контроллеры
     */
    public function sendAllConfigs()
    {
        $error = ConfigMegaService::sendAllConfig();

        if (! $error) {
            $devices = $this->deviceRep->getByName();

            return view('devices.index', compact('devices'));
        } else {
            return back()->withErrors($error);
        }
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($result = $this->service->store($r->except('_token'))) {
                if (is_int($result)) {
                    $id = $result;

                    return redirect()->route('devices.edit', [$id])
                        ->with('success', 'Устройство успешно добавлено');
                }
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении устройства '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении устройства. '.$e->getMessage());
    }

    public function edit(int $id, Request $request)
    {
        $tab = $request['tab'];
        if (! $tab) {
            $tab = 1;
        }

        $device = Device::where('id', $id)->with(
            'ports', 'ports.eobject', 'ports.emethod', 'ports.dcmethod',
            'ports.emethod.eobject', 'ports.dcmethod.eobject', 'ports.lcmethod',
            'extensionModules', 'extensionModules.extensionModuleType', 'ports.lcmethod.eobject'
        )->first();

        if (! $device) {
            return redirect()->route('devices.index')
                ->with('error', 'Устройство не найдено');
        }

        if ($id) {
            $controller = DeviceRepository::getDevByIdDevice($id);
        }

        $sdaSclPorts = $this->extModuleRep->getPortsForModuleByStatus($device, 'I2C');
        $sdaSclOptionsArray = [];
        if (!empty($sdaSclPorts)) {
            foreach ($sdaSclPorts as $key => $value) {
                array_push($sdaSclOptionsArray, ['value' => $value, 'label' => $value]);
            }
        }
        $sdaSclOptionsJson = json_encode($sdaSclOptionsArray);

        $inPorts = $this->extModuleRep->getPortsForModuleByStatus($device, 'IN');
        $intOptionsArray = [];
        if (!empty($inPorts)) {
            foreach ($inPorts as $key => $value) {
                array_push($intOptionsArray, ['value' => $value, 'label' => $value]);
            }
        }
        $intOptionsJson = json_encode($intOptionsArray);

        $moduleTypeOptionsArray = [];
        $extensionModuleTypes = $this->extModuleRep->getModuleTypes();
        if (!empty($extensionModuleTypes)) {
            foreach ($extensionModuleTypes as $key => $value) {
                array_push($moduleTypeOptionsArray, ['value' => $key, 'label' => $value]);
            }
        }
        $moduleTypeOptionsJson = json_encode($moduleTypeOptionsArray);

        return view('devices.edit', compact(
            'device', 'tab', 'sdaSclOptionsJson',
            'moduleTypeOptionsJson', 'intOptionsJson'
        ));
    }
}
