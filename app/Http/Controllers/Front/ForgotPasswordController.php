<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Omnipay\Omnipay;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Models\{AppStyling, AppStylingOption, Currency, Client, Category, Brand, Cart, ReferAndEarn, ClientPreference, Vendor, ClientCurrency, User, Country, UserRefferal, Wallet, WalletHistory, CartProduct, PaymentOption, UserVendor, Permissions, UserPermissions, VendorDocs, VendorRegistrationDocument, EmailTemplate};
use Exception;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends FrontController
{
    use ApiResponser;
    public function getResetPasswordForm(Request $request, $domain = '', $token)
    {
        $updatePassword = DB::table('password_resets')->where(['token' => $token])->first();
        if ($updatePassword) {
            $langId = Session::get('customerLanguage');
            $navCategories = $this->categoryNav($langId);
            return view('frontend/account/resetPassword')->with(['navCategories' => $navCategories, 'token' => $token]);
        }else{
            return redirect()->route('customer.login')->with('error', 'Reset Password link Already Expired');
        }
    }

    public function getResetPasswordSuccess()
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/resetPasswordSuccess')->with(['navCategories' => $navCategories]);
    }

    public function getResetVendorPasswordForm(Request $request, $domain = '', $token)
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/resetVendorPassword')->with(['navCategories' => $navCategories, 'token' => $token]);
    }

    public function resetSuccess($domain = '')
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/resetSuccess')->with(['navCategories' => $navCategories]);
    }
    public function getForgotPasswordForm($domain = '')
    {
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/forgotPassword')->with(['navCategories' => $navCategories]);
    }
    public function postForgotPassword(Request $request, $domain = '')
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => __('The email field is required.'),
            'email.exists' => __('You are not registered with us. Please sign up.'),
        ]);
        
        try {
            $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
            $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
            if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
                $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
                $token = Str::random(60);
                $client_name = $client->name;
                $mail_from = $data->mail_from;
                DB::table('password_resets')->insert(['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]);
                $email_template_content = '';
                $email_template = EmailTemplate::where('id', 3)->first();
                if ($email_template) {
                    $email_template_content = $email_template->content;
                    $email_template_content = str_ireplace("{reset_link}", url('/reset-password/' . $token), $email_template_content);
                }
                $data = [
                    'token' => $token,
                    'mail_from' => $mail_from,
                    'email' => $request->email,
                    'client_name' => $client_name,
                    'logo' => $client->logo['original'],
                    'subject' => $email_template->subject,
                    'email_template_content' => $email_template_content,
                ];
                try {
                    dispatch(new \App\Jobs\sendForgotPasswordEmail($data))->onQueue('forgot_password_email');
                } catch (Exception $e) {
                    return response()->json(['success' => $e->getCode()], 200);
                }
                //dispatch(new \App\Jobs\sendForgotPasswordEmail($data))->onQueue('forgot_password_email');
            }
            return $this->successResponse([], __('We have e-mailed your password reset link!'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**     * Display resetPassword Form     */
    public function postUpdateResetPassword(Request $request, $domain = '')
    {
        $request->validate([
            'token' => 'required',
            'password_confirmation' => 'required',
            'password' => [
                'required',
                'max:50',
                'confirmed',
                Password::min(8)
                    ->numbers()
                    ->letters()
            ],
        ], [
            'password.required' => __('The password field is required.'),
            'password.confirmed' => __('The password confirmation does not match.'),
            'password_confirmation.required' => __('The password confirmation field is required.')
        ]);
        $updatePassword = DB::table('password_resets')->where(['token' => $request->token])->first();
        if ($updatePassword) {
            $user = User::where('email', $updatePassword->email)->update(['password' => Hash::make($request->password)]);
            DB::table('password_resets')->where(['email' => $updatePassword->email])->delete();
            return $this->successResponse([], __('Your password has been changed!'));
        } else {
            return $this->errorResponse(__('Reset Password link has expired!'), 200);
        }
    }
    /**     * Display resetPassword Form     */
    public function postUpdateVendorResetPassword(Request $request, $domain = '')
    {
        $request->validate([
            'token' => 'required',
            'password_confirmation' => 'required',
            'password' => [
                'required',
                'max:50',
                'confirmed',
                Password::min(8)
                    ->numbers()
                    ->letters()
            ],
        ], [
            'password.required' => __('The password field is required.'),
            'password.confirmed' => __('The password confirmation does not match.'),
            'password_confirmation.required' => __('The password confirmation field is required.')
        ]);
        $updatePassword = DB::table('password_resets')->where(['token' => $request->token])->first();
        if ($updatePassword) {

            $user = User::where('email', $updatePassword->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();
            DB::table('password_resets')->where(['email' => $updatePassword->email])->delete();
            
            //send mail
            $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
            $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
            if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
                $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
                $token = Str::random(60);
                $client_name = $client->name;
                $mail_from = $data->mail_from;
                $email_template_content = '';
                $email_template = EmailTemplate::where('id', 9)->first();
                if ($email_template) {
                    $email_template_content = $email_template->content;
                    $email_template_content = str_ireplace("{vendor_name}", $user->name, $email_template_content);
                }
                $data = [
                    'token' => $token,
                    'mail_from' => $mail_from,
                    'email' => $updatePassword->email,
                    'client_name' => $client_name,
                    'logo' => $client->logo['original'],
                    'subject' => $email_template->subject,
                    'email_template_content' => $email_template_content,
                ];
                try {
                    dispatch(new \App\Jobs\sendForgotPasswordEmail($data))->onQueue('forgot_password_email');
                } catch (Exception $th) {
                    return response()->json(['success' => $th->getCode()], 200);
                }
            }
            return $this->successResponse([], __('Your password has been changed!'));
        } else {
            return $this->errorResponse(__('Invalid Token!'), 400);
        }
    }
}
