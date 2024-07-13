<?php

namespace App\Http\Controllers;

use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use Exception;

class DashboardController extends Controller
{
    public function index()
    {
        $ip = '192.168.1.60';  // Gunakan IP address yang benar
        $user = 'admin';
        $pass = 'admin';
        $API = new RouterosAPI();
        $API->debug(false);


        if ($API->connect($ip, $user, $pass)) {
            $identitas = $API->comm('/system/identity/print');
            $port = $API->comm('/system/gps/print');
        } else {
            return 'Koneksi anda gagal';
        }


        $data = [
            'identitas' => $identitas[0]['name'],
            'port' => $port[0]['port'],
        ];
        // dd($identitas);

        return view('dashboard', $data);
    }
}
