<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Models\{Client, Permissions, UserVendor, Country};

class ClientAuth
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
        if (auth()->check()) {
            if (Auth::user()->status == 2) {
                Auth::logout();
                return redirect('login')->with(['account_blocked' => 'Your account has been blocked by admin. Please contact administration.']);
            }
            if (Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1) {
                $route_name = $request->route()->getName();
                $currentPath = request()->path();
                $per_url = explode('/', $currentPath);

                if (Auth::user()->is_superadmin == 0) {
                    if ($route_name == 'customer.edit') {
                        return Redirect::route('client.profile');
                    } elseif ($currentPath == "client/profile") {
                    } elseif ($route_name == 'vendor.show') {
                        $vendor_id = end($per_url);
                        $user_vendors = UserVendor::where([
                            'user_id' => Auth::id(),
                            'vendor_id' => $vendor_id
                        ])->count();
                        if ($user_vendors == 0)
                            return Redirect::route('client.profile');
                    } else {
                        $sub_admin_per = false;
                        $permission_exist = false;
                        $url_path = $per_url[1];
                        $check_if_under_permision = Permissions::get()->pluck('slug')->toArray();
                        if (in_array($url_path, $check_if_under_permision)) {
                            $permission_exist = true;
                        }
                        if ($permission_exist == true) {
                            foreach (Auth::user()->getAllPermissions as $key => $value) {
                                if ($value->permission->slug == $url_path) {
                                    $sub_admin_per = true;
                                }
                            }
                        } else {
                            $sub_admin_per = true;
                        }


                        if ($sub_admin_per == false) {
                            return Redirect::route('client.profile');
                        }
                    }
                }

                return $next($request);
            } else {
                Auth::logout();
                return redirect('login')->with(['account_blocked' => 'You are unauthorized user.']);
            }

            $cl = Client::first();
            $getAdminCurrentCountry = Country::where('id', '=', $cl->country_id)->get()->first();
            if (!empty($getAdminCurrentCountry)) {
                $countryCode = $getAdminCurrentCountry->code;
                $phoneCode = $getAdminCurrentCountry->phonecode;
            } else {
                $countryCode = '';
                $phoneCode = '';
            }

            Session::put('default_country_code', $countryCode);
            Session::put('default_country_phonecode', $phoneCode);
        }
        return redirect('admin/login');
    }
}
