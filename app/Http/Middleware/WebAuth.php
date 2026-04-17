<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\{Client, ClientPreference, ClientLanguage, ClientCurrency};
use Request;
use Config;
use Session;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class WebAuth
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
        if(auth()->check()) {
            if(Auth::user()->status == 2){
                Auth::logout();
                return redirect('login')->with(['account_blocked' => 'Your account has been blocked by admin. Please contact administration.']);
            }
            return $next($request);
        }
        return redirect('user/login');
        
    }
}