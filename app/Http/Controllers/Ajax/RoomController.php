<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Device;
use App\Port;
use Illuminate\Http\Request;
=======
use App\Services\RoomService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
>>>>>>> 60de956102a593f31582326fc280ce710437f7e7

class DevicesController extends Controller
{
<<<<<<< HEAD
    public function index()
    {

        $devices = Device::select('devices.*', 'devtypes.name AS type')
            ->join('devtypes','devtypes.id','=','devices.type')->get();

      return view('devices',['devices'=>$devices]);
    }

    /**
     * Выбор определенного устройства по id
     *
     * @param int $id - ид устройсва, коотрое выбираем
     * @return void
    */
    public function select($id)
    {
        //получение инормации об устройстве по овыбранному id
        $device = Device::where('id',$id)->first();

        //получение портов устройства и связанных с ними свойств
        $ports = Port::select('*','ports.id AS id', 'ports.status AS type','objects.name AS nameobj', 'scripts.name AS namescript')
                        ->leftjoin('objects','objects.id','=','ports.object')
                        ->leftjoin('scripts','scripts.id','=','ports.script')
                        ->orderBy('num_port', 'ASC')
                        ->where('id_device',$id)->get();


        return view('device',['device'=>$device, 'ports'=>$ports]);
    }

=======
    private $service;

    public function __construct(RoomService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!ajaxHas($r, ['id']), 400);

        return response()->json(['result' => $this->service->delete((int)$r->id)]);
    }

    public function sort(Request $r)
    {
        abort_if(!ajaxHas($r, ['id', 'direction']), 400);

        return response()->json(['result' => $this->service->sort($r->all())]);
    }

    public function store(Request $r)
    {
        abort_if(!ajaxHas($r, ['name', 'image', 'style']), 400);

        return response()->json(['result' => (bool)$this->service->store($r->all())]);
    }

    public function updateName(Request $r)
    {
        abort_if(!ajaxHas($r, ['id', 'name']), 400);

        $this->service->updateName((int)$r->id, (string)$r->name);

        return response()->json(['success' => true, 'html' => $r->name]);
    }

    public function updateImage(Request $r)
    {
        abort_if(!ajaxHas($r, ['id', 'image']), 400);

        $this->service->updateImage((int)$r->id, (string)$r->image);

        return response()->json(['result' => true]);
    }

    public function updateColor(Request $r)
    {
        abort_if(!ajaxHas($r, ['id', 'color']), 400);

        $this->service->updateColor((int)$r->id, (string)$r->color);

        return response()->json(['result' => true]);
    }
>>>>>>> 60de956102a593f31582326fc280ce710437f7e7
}
