<?php

namespace App\Http\Controllers\Client;

use App\Models\SmsProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, ClientPreference, MapProvider};

class ConfigureController extends BaseController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $mapTypes = MapProvider::where('status', '1')->get();
        $smsTypes = SmsProvider::where('status', '1')->get();
        $preference = ClientPreference::where('client_code',Auth::user()->code)->first();
        $client = Auth::user();
        if(!$preference){
            $preference = new ClientPreference();
        }
        return view('backend/setting/config')->with(['client' => $client, 'preference' => $preference, 'maps'=> $mapTypes, 'smsTypes' => $smsTypes]);
    }
}
