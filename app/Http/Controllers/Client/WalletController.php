<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Celebrity, Wallet, WalletHistory};

class WalletController extends BaseController{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $wallets = Wallet::with('user')->get();
        return view('backend/wallet/index')->with(['wallets' => $wallets]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id){
        $wallets = Wallet::where('id', $id)->first();
        $returnHTML = view('backend.wallet.form')->with(['lc' => $wallets])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($domain = '', Request $request, $id)
    {
        $rules = array('amount' => 'required|numeric');
        $validation  = Validator::make($request->all(), $rules)->validate();
        $wallet = Wallet::where('id', $id)->firstOrFail();
        $wallet->balance = (int)$request->input('amount') + $wallet->balance;
        $wallet->save();
        $wallet_history = new WalletHistory();
        $wallet_history->user_id = $wallet->user_id;
        $wallet_history->wallet_id = $wallet->id;
        $wallet_history->amount = $request->amount;
        $wallet_history->save();
        return response()->json([
            'data' => $wallet,
            'status' => 'success',
            'message' => 'Loyalty Card updated Successfully!',
        ]);
    }
}
