<?php

namespace App\Http\Controllers;

use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class PPPoEController extends Controller
{
    public function index()
    {

        $ip = session()->get('ip');  // Gunakan IP address yang benar
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(false);


        if ($API->connect($ip, $user, $pass)) {
            $secret = $API->comm('/ppp/secret/print');
            $profile = $API->comm('/ppp/profile/print');
        } else {
            return 'gagal lagi';
        }


        $data = [
            'totalsecret' => count($secret),
            'secret' => $secret,
            'profile' => $profile,
        ];

        // dd($data);

        return view('pppoe.secret', $data);
    }

    public function add(Request $request)
    {



        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');
        $API = new RouterosAPI();
        $API->debug(true); // Enable debugging

        if ($API->connect($ip, $user, $pass)) {
            try {
                $API->comm('/ppp/secret/add', [
                    'name' => $request->input('user'),
                    'password' => $request->input('password'),
                    'service' => $request->input('service') == '' ? 'any' : $request->input('service'),
                    'profile' => $request->input('profile') == '' ? 'default' : $request->input('profile'),
                    'local-address' => $request->input('localaddress') == '' ? '0.0.0.0' : $request->input('localaddress'),
                    'remote-address' => $request->input('remoteaddress') == '' ? '0.0.0.0' : $request->input('remoteaddress'),
                    'comment' => $request->input('comment') == '' ? '' : $request->input('comment'),
                ]);
                // Optionally, log the successful addition
                Log::info('PPP secret added successfully: ' . $request->input('user'));
            } catch (\Exception $e) {
                // Log or handle the exception
                Log::error('Failed to add PPP secret: ' . $e->getMessage());
                return 'Failed to add PPP secret';
            } finally {
                $API->disconnect(); // Always disconnect after API usage
            }
        } else {
            return 'Failed to connect to API';
        }

        return redirect()->route('pppoe.secret');
    }
}
