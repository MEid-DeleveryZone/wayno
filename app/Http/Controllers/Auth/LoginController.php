<?php

namespace App\Http\Controllers\Auth;

use DB;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Cache;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('guest')->except('logout');
    }

    public function getClientLogin(){
        return view('auth.login');
    }
    
    public function clientLogin(Request $request){
        $this->validate($request, [
            'email'           => 'required|max:255|email',
            'password'        => 'required',
        ]);
        $guard = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if ($guard) {
            $client = Client::with('preferences')->first();
            if($client->is_blocked == 1 || $client->is_deleted == 1){
                Auth::logout();
               return redirect()->back()->with('Error', 'Your account has been blocked by admin. Please contact administration.');
            }
            $client = User::where('email',$request->email)->first();
            if($client->is_superadmin == 1 || $client->is_admin == 1){
               
                return redirect()->route('client.dashboard');
            }else{
                Auth::logout();
                return redirect()->back()->with('Error', 'You are unauthorized user.');
            }
        }
        return redirect()->back()->with('Error', 'Invalid Credentials');
    }

    public function Logout(){   
        Auth::guard('client')->logout();
        Auth::logout();
        if (!empty(Session::get('current_fcm_token'))) {
            UserDevice::where('device_token', Session::get('current_fcm_token'))->delete();
            Session::forget('current_fcm_token');
        }
        return redirect()->route('admin.login');
    }

    public function wrongurl(){
        return redirect()->route('wrong.client');
    }

    public function showLoginForm(){
        return redirect()->to('/');
    }
}
