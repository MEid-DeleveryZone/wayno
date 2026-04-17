<?php

namespace App\Http\Controllers\Api\v1;
use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Models\{User, Transaction};
use App\Http\Controllers\Controller;

class WalletController extends Controller{
    use ApiResponser;

    # get my wallet details 
    public function getFindMyWalletDetails(Request $request){
    	$user = Auth::user();
        $user = User::with('country')->find($user->id);
        $paginate = $request->has('limit') ? $request->limit : 12;
        $transactions = Transaction::where('payable_id', $user->id)->orderBy('id', 'desc')->paginate($paginate);
        foreach($transactions as $trans){
            $trans->meta = json_decode($trans->meta);
            $trans->amount = sprintf("%.2f", $trans->amount / 100);
        }
        $data = ['wallet_amount' => $user->balanceFloat, 'transactions' => $transactions];
        return $this->successResponse($data, '', 200);
    }


    # credit wallet set 
    public function creditMyWallet(Request $request)
    {   
        $user = User::whereHas('device',function  ($qu) use ($request){
            $qu->where('access_token', $request->auth_token);
        })->first();

       
        if($user){
            $credit_amount = $request->amount;
            $wallet = $user->wallet;
            if ($credit_amount > 0) {
                $wallet->depositFloat($credit_amount, ['Wallet has been <b>Credited</b> by transaction reference <b>'.$request->transaction_id.'</b>']);
                $transactions = Transaction::where('payable_id', $user->id)->get();
                $response['wallet_balance'] = $wallet->balanceFloat;
                $response['transactions'] = $transactions;
                $message = 'Wallet has been credited successfully';
                return $this->successResponse($response, $message, 201);
            }
            else{
                return $this->errorResponse('Amount is not sufficient', 402);
            }
        }
        else{
            return $this->errorResponse('Invalid User', 402);
        }
    }
}
