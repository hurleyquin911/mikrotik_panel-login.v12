<?php

namespace App\Http\Controllers;

use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use Exception;

class DashboardController extends Controller
{
    public function index()
    {
        //statis ip address 192.168.1.60

        $ip = session()->get('ip');  // Gunakan IP address yang benar
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);


        if ($API->connect($ip, $user, $pass)) {
            $identitas = $API->comm('/system/identity/print');
            $port = $API->comm('/system/history/print');
        } else {
            return 'Koneksi anda gagal';
        }


        $data = [
            'identitas' => $identitas[0]['name'],
            'port' => $port,
        ];

        // dd($identitas); 

        return view('dashboard', $data);
    }
}
