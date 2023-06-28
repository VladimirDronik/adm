<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 11.04.21
 * Time: 11:32
 */

namespace App\Http\Controllers;
use App\Models\Boiler;
use App\Repositories\BoilerRepository;
use App\Http\Requests\Boiler\UpdateRequest;
use App\Http\Requests\Boiler\CreateRequest;
use App\Repositories\TermostatRepository;
use App\Services\BoilerService;

class BoilerController extends Controller
{
    private $termostatRepository;
    private $boilerRepository;
    private $service;

    public function __construct(
        BoilerRepository $boilerRepository,
        BoilerService $boilerService,
        TermostatRepository $termostatRepository
    )
    {
        $this->termostatRepository = $termostatRepository;
        $this->boilerRepository = $boilerRepository;
        $this->service = $boilerService;
    }

    public function edit($boilerIdObject)
    {
        $boiler = $this->boilerRepository->getBoiler($boilerIdObject);
        $termostats = $this->termostatRepository->getAllWithIdObjectToArray();

        return view('engineering.boiler.edit', compact('boiler', 'termostats'));
    }


    public function update(UpdateRequest $r, int $idObject)
    {
        $boiler = $this->boilerRepository->getBoiler($idObject);

        try {
            if ($this->service->update($boiler, $r->except('_token'))) {
                return redirect()->route('boiler.edit',[$boiler->id_object])->with('success','Настройки успешно изменены');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении настроек котла '.$boiler->id_object.' '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении настроек котла');
    }



    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('engineering.index')
                    ->with('success', 'Котёл успешно добавлен')
                    ->with('idObject', $id);
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении котла ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении котла');
    }


    public function create()
    {
        $typesBoiler = Boiler::getTypes();
        $termostats = $this->termostatRepository->getAllWithIdObjectToArray();

        return view('engineering.boiler.create', compact('typesBoiler', 'termostats'));
    }

}
