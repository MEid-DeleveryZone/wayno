<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Adminlanguageswitch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
       
        if(session()->has('applocale_admin')){
            app()->setlocale(session()->get("applocale_admin"));
        }else{
            app()->setlocale('en');
        }
        return $next($request);
    }
}
