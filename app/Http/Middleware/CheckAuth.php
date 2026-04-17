<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use App\Models\{BlockedToken, User, ClientLanguage, ClientCurrency};
use Illuminate\Support\Facades\Cache;
use Request;
use Config;
use Illuminate\Support\Facades\DB;
use JWT\Token;
use Auth;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header();
        
        $user = new User();
        if (isset($header['authorization']) && Token::check($header['authorization'][0], 'royoorders-jwt')){
            $token = $header['authorization'][0];
            $tokenBlock = BlockedToken::where('token', $token)->first();
            if($tokenBlock){
                return response()->json(['error' => 'Invalid Session', 'message' => 'Session Expired'], 401);
                abort(404);
            }
            $user = User::whereHas('device', function($qu) use ($token){
                $qu->where('access_token', $token);
            })->first();

           
            if(!$user){
                return response()->json(['error' => 'Invalid Session', 'message' => 'Invalid Token or session has been expired.'], 401);
                abort(404);
            }
        }
        if(isset($header['systemuser'])){
            $systemUser = $header['systemuser'][0];
            $user->system_user = $systemUser;
        }

        $languages = ClientLanguage::where('is_primary', 1)->first();
        $primary_cur = ClientCurrency::where('is_primary', 1)->first();

        // HYBRID APPROACH: Priority system
        // 1. Try to get from user's saved preference (if user exists and has preference)
        // 2. Fall back to primary language/currency
        $language_id = $user->language_id ?? ($languages ? $languages->language_id : 1);
        $currency_id = $user->currency_id ?? ($primary_cur ? $primary_cur->currency_id : 1);

        // 3. Override with request header if provided (highest priority)
        if(isset($header['language'][0]) && !empty($header['language'][0])){
            $langValue = $header['language'][0];
            
            // Check if the value is numeric (old way: language_id) or string (new way: language code like 'en', 'ae')
            if(is_numeric($langValue)){
                // Backward compatibility: support numeric language_id
                $checkLang = ClientLanguage::where('language_id', $langValue)->first();
                if($checkLang){
                    $language_id = $checkLang->language_id;
                }
            } else {
                // New way: support language codes like 'en', 'ae', 'ar'
                $checkLang = ClientLanguage::whereHas('language', function($q) use ($langValue) {
                    $q->where('sort_code', $langValue);
                })->first();
                if($checkLang){
                    $language_id = $checkLang->language_id;
                }
            }
        }

        if(isset($header['currency'][0]) && !empty($header['currency'][0])){
            $checkCur = ClientCurrency::where('currency_id', $header['currency'][0])->first();
            if($checkCur){
                $currency_id = $checkCur->currency_id;
            }
        }
        if(isset($header['latitude'][0]) && !empty($header['latitude'][0])){
            $user->latitude = $header['latitude'][0];
        }
        if(isset($header['longitude'][0]) && !empty($header['longitude'][0])){
            $user->longitude = $header['longitude'][0];
        }
        $user->language = $language_id;
        $user->currency = $currency_id;
        
        Auth::login($user);

        return $next($request);
        
    }
}