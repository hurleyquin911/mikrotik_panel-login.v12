<?php

namespace App\Http\Controllers;

use App\Models\RouterosAPI;
use Iluminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {

        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);


        if ($API->connect($ip, $user, $pass)) {
            $identitas = $API->comm('/system/identity/print');
            $port = $API->comm('/system/history/print');
        } else {
            return 'Koneksi kamu gagal cobalagi lah';
        }


        $data = [
            'identitas' => $identitas[0]['name'],
            'port' => $port,
        ];

        // dd($identitas);

        return view('dashboard', $data);
    }
}
