<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkPaidCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user) {
            $paidCart = Cart::where('user_id', $user->id)->where('paid', 1)->first();
            if ($paidCart) {
                return response()->json([
                    'message' => 'You have one unfinished order. Contact our support!'
                ], 500);
                abort(500);
            }
        }
        return $next($request);
    }
}
