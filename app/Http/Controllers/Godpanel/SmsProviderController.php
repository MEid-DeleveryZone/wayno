<?php

namespace App\Http\Controllers\Godpanel;

use App\Models\SmsProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SmsProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sms = SmsProvider::orderBy('created_at', 'DESC')->paginate(10);
        return view('godpanel/sms')->with(['sms' => $sms]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sms = new SmsProvider();
        return view('godpanel/sms-form')->with(['sms' => $sms]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'add');
        }

        $data = [
            'provider' => $request->provider,
            'keyword' => strtolower(str_replace(' ', '_', $request->provider)),
            'status' => $request->status,
        ];

        $client = SmsProvider::create($data);
        return redirect()->route('sms.index')->with('success', 'Sms provider added successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SmsProvider  $smsProvider
     * @return \Illuminate\Http\Response
     */
    public function show(SmsProvider $smsProvider)
    {
        //phpinfo();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SmsProvider  $smsProvider
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsProvider $smsProvider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SmsProvider  $smsProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmsProvider $smsProvider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SmsProvider  $smsProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, SmsProvider $smsProvider)
    {
        $sms_prov = SmsProvider::findOrFail($id);
        if($sms_prov->keyword == 'twilio'){
            return redirect()->back()->with('error_delete', 'Twilio sms provider is not deletable!');
        }
        $sms_prov->delete();
        return redirect()->back()->with('success', 'Sms provider deleted successfully!');

    }
}
