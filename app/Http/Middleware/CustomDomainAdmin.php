<?php

namespace App\Http\Middleware;

use Cache;
use Config;
use Session;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redis;
use App\Models\{Client, ClientPreference, ClientLanguage};

class CustomDomainAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
      $domain = $request->getHost();
      $subDomain = explode('.', $domain);
      return $next($request);
    }
}