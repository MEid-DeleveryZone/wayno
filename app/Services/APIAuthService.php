<?php

namespace App\Services;

use App\Models\BlockedToken;
use App\Models\ClientCurrency;
use App\Models\ClientLanguage;
use App\Models\User;
use Illuminate\Http\Request;
use JWT\Token;
use Auth;

// This trait is used to check if the user is logged-in in the APIs without using the middleware
// So that we can customize the response messages, other than the middlware message
// If a valid token is present in the request this Train will login the user

trait APIAuthService
{

    // This function returns true if the API authentication is success, or false.
    protected function isLoggedIn(Request $request)
    {
        $header = $request->header();
        $token = $header['authorization'][0] ?? null;
        if (!$token) {
            return false;
        }
        if ($token && !Token::check($token, 'royoorders-jwt')) {
            return false;
        }
        $tokenBlock = BlockedToken::where('token', $token)->first();
        if ($tokenBlock) {
            return false;
        }
        $user = User::whereHas('device', function ($qu) use ($token) {
            $qu->where('access_token', $token);
        })->first();

        if (!$user) {
            false;
        }

        //Login the user if the token is valid

        $timezone = $user->timezone;
        $languages = ClientLanguage::where('is_primary', 1)->first();
        $primary_cur = ClientCurrency::where('is_primary', 1)->first();
        $language_id = $languages->language_id;
        $currency_id = $primary_cur->currency_id;
        if(isset($header['language'][0]) && !empty($header['language'][0])){
            $checkLang = ClientLanguage::where('language_id', $header['language'][0])->first();
            if($checkLang){
                $language_id = $checkLang->language_id;
            }
        }
        if(isset($header['currency'][0]) && !empty($header['currency'][0])){
            $checkCur = ClientCurrency::where('currency_id', $header['currency'][0])->first();
            if($checkCur){
                $currency_id = $checkCur->currency_id;
            }
        }
        if(isset($header['timezone'][0]) && !empty($header['timezone'][0])){
            $timezone = $header['timezone'][0];
        }
        if(isset($header['latitude'][0]) && !empty($header['latitude'][0])){
            $user->latitude = $header['latitude'][0];
        }
        if(isset($header['longitude'][0]) && !empty($header['longitude'][0])){
            $user->longitude = $header['longitude'][0];
        }
        $user->language = $language_id;
        $user->currency = $currency_id;
        $user->timezone = $timezone;
        Auth::login($user);

        return TRUE;
    }
}
