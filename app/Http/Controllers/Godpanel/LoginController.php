<?php

namespace App\Http\Controllers\Godpanel;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
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

    public function Login(Request $request){
        $this->validate($request, [
            'email'           => 'required|max:255|email',
            'password'        => 'required',
        ]);
        $guard = Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password]);
        if ($guard) {
            $admin = Admin::where('email', $request->email)->first();
            Auth::login($admin);
            $details = Auth::guard('admin')->user();
            $user = $details['original'];
            return redirect()->route('client.index');
        } else {
            return redirect()->back()->with('Error', 'Invalid Credentials');
        }
    }

    public function Logout()
    {
        Auth::logout();
        return redirect()->route('god.login');
    }
}
