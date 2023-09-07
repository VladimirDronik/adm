<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 11.04.21
 * Time: 11:32
 */

namespace App\Http\Controllers;
use App\Models\BoilerGVS;
use App\Repositories\BoilerGVSRepository;
use App\Http\Requests\Boiler_gvs\UpdateRequest;
use App\Http\Requests\Boiler_gvs\CreateRequest;
use App\Services\BoilerGVSService;


class BoilerGVSController extends Controller
{
    private $boilerRepository;
    private $service;

    public function __construct(BoilerGVSRepository $boilerRepository, BoilerGVSService $boilerService)
    {

        $this->boilerRepository = $boilerRepository;
        $this->service = $boilerService;

    }

    public function edit($boilerIdObject)
    {
        $boiler = $this->boilerRepository->getBoiler($boilerIdObject);
        return view('engineering.boiler_gvs.edit', compact('boiler'));
    }


    public function update(UpdateRequest $r, int $idObject)
    {
        $boiler = $this->boilerRepository->getBoiler($idObject);

        try {
            if ($this->service->update($boiler, $r->except('_token'))) {
                return redirect()->route('boiler_gvs.edit',[$boiler->id_object])->with('success','Настройки успешно изменены');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении настроек бойлера ГВС '.$boiler->id_object.' '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении настроек бойлера ГВС');
    }



    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('engineering.index')
                    ->with('success', 'Бойлер ГВС успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении бойлера ГВС ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении бойлера ГВС');
    }


    public function create()
    {
        $typesBoiler = BoilerGVS::getTypes();
        return view('engineering.boiler_gvs.create', compact('typesBoiler'));
    }

}