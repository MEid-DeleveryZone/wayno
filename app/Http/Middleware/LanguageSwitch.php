<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LanguageSwitch
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
        if (session()->has('locale')) {
            app()->setlocale(session()->get("locale"));
        } elseif (session()->has('applocale')) {
            app()->setlocale(session()->get("applocale"));
        } else {
            app()->setlocale('en');
        }
        return $next($request);
    }
}
