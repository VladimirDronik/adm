<?php

namespace App\Http\Controllers;

use App\Services\NetworkService;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function edit()
    {
        try {
            $nw = new NetworkService();

            $nw->setIface("192.168.0.100", "255.255.255.0", "192.168.0.1");

            $nw->setIface("10.0.0.4", "255.255.255.0");

            $nw->setVpn("5.9.2.43", "username", "password");

            $network = (object)[
                'id' => 1,
                'ip' => '12.12.12.12'
            ];

        } catch (\Throwable $e) {
            dd($e->getMessage());
        }

        return view('network.edit', compact('network'));
    }
}