<?php

namespace App\Http\Controllers;

use App\Device;
use App\Port;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
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

}
