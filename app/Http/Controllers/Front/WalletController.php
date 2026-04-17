<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use App\Models\{User, Transaction, ClientCurrency, PaymentOption};
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Session;

class WalletController extends FrontController
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $langId = Session::get('customerLanguage');
        $currency_id = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        $user = User::with('country')->find(Auth::user()->id);
        $navCategories = $this->categoryNav($langId);
        $auth_user = Auth::user();
        $user_transactions = Transaction::where('payable_id', $auth_user->id)->orderBy('id', 'desc')->paginate(10);
        return view('frontend/account/wallet')->with(['user'=>$user, 'navCategories'=>$navCategories, 'user_transactions'=>$user_transactions, 'clientCurrency'=>$clientCurrency]);
    }

    /**
     * Credit Money Into Wallet
     *
     * @return \Illuminate\Http\Response
     */
    public function creditWallet(Request $request, $domain = '')
    {
        if( (isset($request->user_id)) && (!empty($request->user_id)) ){
            $user = User::find($request->user_id);
        }elseif( (isset($request->auth_token)) && (!empty($request->auth_token)) ){
            $user = User::whereHas('device',function  ($qu) use ($request){
                $qu->where('access_token', $request->auth_token);
            })->first();

        }else{
            $user = Auth::user();
        }
        if($user){
            $credit_amount = $request->wallet_amount;
            $wallet = $user->wallet;
            if ($credit_amount > 0) {
                $saved_transaction = Transaction::where('meta', 'like', '%'.$request->transaction_id.'%')->first();
                if($saved_transaction){
                    return $this->errorResponse('Transaction has already been done', 400);
                }

                $wallet->depositFloat($credit_amount, ['Wallet has been <b>Credited</b> by transaction reference <b>'.$request->transaction_id.'</b>']);
                $transactions = Transaction::where('payable_id', $user->id)->get();
                $response['wallet_balance'] = $wallet->balanceFloat;
                $response['transactions'] = $transactions;
                $message = 'Wallet has been credited successfully';
                Session::put('success', $message);
                return $this->successResponse($response, $message, 200);
            }
            else{
                return $this->errorResponse('Amount is not sufficient', 400);
            }
        }
        else{
            return $this->errorResponse('Invalid User', 400);
        }
    }

    /**
     * Credit Money Into Wallet Through gateway redirection
     *
     * @return \Illuminate\Http\Response
     */
    public function postPaymentCreditWallet(Request $request, $domain = '')
    {
        if( (isset($request->auth_token)) && (!empty($request->auth_token)) ){
            return $this->creditWallet($request);
        }else{
            return $this->errorResponse('Invalid User', 402);
        }
    }

    /**
     * wallet payment options
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentOptions(Request $request, $domain = ''){
        $ex_codes = ['cod'];
        $payment_options = PaymentOption::select('id', 'code', 'title', 'credentials')->whereNotIn('code', $ex_codes)->where('status', 1)->get();
        foreach ($payment_options as $k => $payment_option) {
            if( (!empty($payment_option->credentials)) ){
                $payment_option->slug = strtolower(str_replace(' ', '_', $payment_option->title));
                unset($payment_option->credentials);
            }
            else{
                unset($payment_options[$k]);
            }
        }
        return $this->successResponse($payment_options);
    }
}
