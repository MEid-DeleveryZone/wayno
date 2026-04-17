<?php

namespace App\Http\Controllers\Front;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Models\{AppStyling, AppStylingOption, Currency, Client, Category, Brand, Cart, ReferAndEarn, ClientPreference, Vendor, ClientCurrency, User, Country, UserRefferal, Wallet, WalletHistory, CartProduct, PaymentOption, UserVendor,Permissions, UserPermissions, VendorDocs, VendorRegistrationDocument, EmailTemplate, NotificationTemplate, UserDevice, VendorCategory};
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class CustomerAuthController extends FrontController
{
    use ApiResponser;
    public function getTestHtmlPage()
    {
        return view('test');
    }

    public function getDemoCabBookingPage()
    {
        return view('cabdemo');
    }

    public function fcm()
    {
        return view('firebase');
    }

    public function sendNotification(){
        // $token = ["fXC1tzHiywg:APA91bGj3YXxPXuiBjCSAhlt0leikG2eq2gIJm3EFtSjkfp4c6akzpeDOqq2XfvUxxX99i36aCPf8gFsJIZrU7Ywcx6ZCIMh9vAPJctpxyU0_pagKF-wgVURZ2Z6C6XMaWAFZCDlas3L"];
        $token = ["SYyGlsuFhM:APA91bHT-EWdEXBqn9hqpTLObtI8VNf49QTatYDSFaYeI4OZG6vGrxKyfPZUe2b8h0KMhwS-pZCC1stmHlqCqMKdLD5baCjnCusdHZys9Z31gpykmUg2fS5PwmcfAYHB11EAZi_dHHqq"];
       //previous
    //    $from = 'AAAA-gxQcf4:APA91bF2-7wHcDDUpdnOAjPkRECMcMqZyto1g3CloNTSvp4tvaM6yX2H1H3FFWQj3mHE_t0LkKKu5M_ASTjIaKvvuLDTrXe9eO7Xi7k8YbH6M355gz7x0GTbK7E9F7I7CAQS3AILs4J_';
        $from = 'AAAA8Aea-wU:APA91bEKYAjmXEjMg4u-fdxZuDtmwtM_rdkJ8d06mQfGtAiZnZIBYrH5LvYLTWh9VJjMD3pAHaJzjbvL-ckV43xJBHXrLxBvop0SAOwUSQ6Un7_OAEmuzbrHbjBpq045nyWYz4d5zP6b';

        $notification_content = NotificationTemplate::where('id', 3)->first();
        if($notification_content){
            $headers = [
                'Authorization: key=' . $from,
                'Content-Type: application/json',
            ];
            $data = [
                "registration_ids" => $token,
                "notification" => [
                    'title' => "Title message",
                    'body'  => "Sample Body Message",
                ]
            ];
            $dataString = $data;

            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $dataString ) );
            $result = curl_exec($ch );
            dd($result);
            curl_close( $ch );
        }
    }

    public function loginForm($domain = '')
    {
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend.account.loginnew')->with(['navCategories' => $navCategories]);
    }

    public function registerForm($domain = '', Request $request)
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);


        if (!Session::get('referrer')) {
            return view('frontend.account.registernew')->with(['navCategories' => $navCategories]);
        } else {
            return view('frontend.account.registernew')->with(['navCategories' => $navCategories, 'code' => Session::get('referrer')]);
        }
    }

    /**     * check if cookie already exist     */
    public function checkCookies($userid){
        if (Cookie::has('uuid')) {
            $existCookie = Cookie::get('uuid');
            $userFind = User::where('system_id', $existCookie)->first();
            if ($userFind) {
                $cart = Cart::where('user_id', $userFind->id)->first();
                if ($cart) {
                    $cart->user_id = $userid;
                    $cart->save();
                }
                $userFind->delete();
            }
            Cookie::queue(Cookie::forget('uuid'));
            return redirect()->route('user.checkout');
        }
    }

    /**     * Display login Form     */
    public function login(LoginRequest $req, $domain = ''){
        if (Auth::attempt(['email' => $req->email, 'password' => $req->password, 'status' => 1])) {
            $userid = Auth::id();
            if($req->has('access_token')){
                if($req->access_token){
                    $user_device = UserDevice::where('user_id', $userid)->where('device_token', $req->access_token)->first();
                    if(!$user_device){
                        $user_device = new UserDevice();
                        $user_device->user_id = $userid;
                        $user_device->device_type = 'web';
                        $user_device->device_token = $req->access_token;
                        $user_device->save();
                    }
                }
            }
            $this->checkCookies($userid);
            $user_cart = Cart::where('user_id', $userid)->first();
            if ($user_cart) {
                $unique_identifier_cart = Cart::where('unique_identifier', session()->get('_token'))->first();
                if ($unique_identifier_cart) {
                    $unique_identifier_cart_products = CartProduct::where('cart_id', $unique_identifier_cart->id)->get();
                    foreach ($unique_identifier_cart_products as $unique_identifier_cart_product) {
                        $user_cart_product_detail = CartProduct::where('cart_id', $user_cart->id)->where('product_id', $unique_identifier_cart_product->product_id)->first();
                        if ($user_cart_product_detail) {
                            $user_cart_product_detail->quantity = ($unique_identifier_cart_product->quantity + $user_cart_product_detail->quantity);
                            $user_cart_product_detail->save();
                            $unique_identifier_cart_product->delete();
                        } else {
                            $unique_identifier_cart_product->cart_id = $user_cart->id;
                            $unique_identifier_cart_product->save();
                        }
                    }
                    $unique_identifier_cart->delete();
                }
            } else {
                Cart::where('unique_identifier', session()->get('_token'))->update(['user_id' => $userid, 'created_by' => $userid, 'unique_identifier' => '']);
            }
            if(session()->has('url.intended')){
                $url = session()->get('url.intended');
                session()->forget('url.intended');
                return redirect($url);
            }else{
                return redirect()->route('user.verify');
            }
        }
        $checkEmail = User::where('email', $req->email)->first();
        if ($checkEmail) {
            return redirect()->back()->with('err_password', __('Password not matched. Please enter correct password.'));
        }
        return redirect()->back()->with('err_email', __('Email not exist. Please enter correct email.'));
    }


    /**     * Display register Form     */
    public function register(SignupRequest $req, $domain = ''){
        try {
            if( (empty($req->email)) && (empty($req->phone_number)) ){
                $validator = $req->validate([
                    'email'  => 'required',
                    'phone_number'  => 'required'
                ],[
                    "email.required" => __('The email or phone number field is required.'),
                    "phone_number.required" => __('The email or phone number field is required.'),
                ]);
            }
            else{
                $preferences = ClientPreference::first();
                if(!empty($req->email) && ($preferences->verify_email == 0)){
                    $validator = $req->validate([
                        'email'  => 'email|unique:users'
                    ]);
                }
                if(!empty($req->phone_number) && isset($preferences) && ($preferences->verify_phone == 0)){
                    $validator = $req->validate([
                        'phone_number' => 'string|min:8|max:15|unique:users'
                    ]);
                }
            }
            $user = new User();
            $county = Country::where('code', strtoupper($req->countryData))->first();
            $phoneCode = mt_rand(100000, 999999);
            $emailCode = mt_rand(100000, 999999);
            $sendTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
            $email = (!empty($req->email)) ? $req->email : ('ro_'.Carbon::now()->timestamp . '.' . uniqid() . '@royoorders.com');
            $user->type = 1;
            $user->status = 1;
            $user->role_id = 1;
            $user->name = $req->name;
            $user->email = $email;
            $user->is_email_verified = 0;
            $user->is_phone_verified = 0;
            $user->country_id = $county->id;
            $user->phone_token = $phoneCode;
            $user->dial_code = $req->dialCode;
            $user->email_token = $emailCode;
            $user->phone_number = $req->phone_number;
            $user->phone_token_valid_till = $sendTime;
            $user->email_token_valid_till = $sendTime;
            $user->password = Hash::make($req->password);
            $user->save();
            $wallet = $user->wallet;
            $userRefferal = new UserRefferal();
            $userRefferal->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
            if ($req->refferal_code != null) {
                $userRefferal->reffered_by = $req->refferal_code;
            }
            $userRefferal->user_id = $user->id;
            $userRefferal->save();
            if ($user->id > 0) {
                if ($req->refferal_code != null) {
                    $refferal_amounts = ClientPreference::first();
                    if ($refferal_amounts) {
                        if ($refferal_amounts->reffered_by_amount != null && $refferal_amounts->reffered_to_amount != null) {
                            $reffered_by = UserRefferal::where('refferal_code', $req->refferal_code)->first();
                            $user_refferd_by = $reffered_by->user_id;
                            $user_refferd_by = User::where('id', $reffered_by->user_id)->first();
                            if ($user_refferd_by) {
                                //user reffered by amount
                                $wallet_user_reffered_by = $user_refferd_by->wallet;
                                $wallet_user_reffered_by->depositFloat($refferal_amounts->reffered_by_amount, ['Referral code used by <b>' . $req->name . '</b>']);
                                $wallet_user_reffered_by->balance;
                                //user reffered to amount
                                $wallet->depositFloat($refferal_amounts->reffered_to_amount, ['You used referral code of <b>' . $user_refferd_by->name . '</b>']);
                                $wallet->balance;
                            }
                        }
                    }
                }
                Auth::login($user);
                $this->checkCookies($user->id);
                Session::forget('referrer');
                $prefer = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username',
                        'mail_password', 'mail_encryption', 'mail_from', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from',
                        'theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider',
                        'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
                if(!empty($prefer->sms_key) && !empty($prefer->sms_secret) && !empty($prefer->sms_from)){
                    $response['send_otp'] = 1;
                    $to = '+'.$user->dial_code.$user->phone_number;
                    $provider = $prefer->sms_provider;
                    $body = "Dear ".ucwords($user->name).", Please enter OTP ".$phoneCode." to verify your account.";
                    $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
                }
                if(!empty($prefer->mail_driver) && !empty($prefer->mail_host) && !empty($prefer->mail_port) && !empty($prefer->mail_port) && !empty($prefer->mail_password) && !empty($prefer->mail_encryption)){
                    $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
                    $confirured = $this->setMailDetail($prefer->mail_driver, $prefer->mail_host, $prefer->mail_port, $prefer->mail_username, $prefer->mail_password, $prefer->mail_encryption);
                    $client_name = $client->name;
                    $mail_from = $prefer->mail_from;
                    $sendto = $req->email;
                    try {
                        $email_template_content = '';
                        $email_template = EmailTemplate::where('id', 2)->first();
                        if($email_template){
                            $email_template_content = $email_template->content;
                            $email_template_content = str_ireplace("{code}", $emailCode, $email_template_content);
                            $email_template_content = str_ireplace("{customer_name}", ucwords($user->name), $email_template_content);
                        }
                        $data = [
                            'code' => $emailCode,
                            'link' => "link",
                            'email' => $sendto,
                            'mail_from' => $mail_from,
                            'client_name' => $client_name,
                            'logo' => $client->logo['original'],
                            'subject' => $email_template->subject,
                            'customer_name' => ucwords($user->name),
                            'email_template_content' => $email_template_content,
                        ];
                        dispatch(new \App\Jobs\SendVerifyEmailJob($data))->onQueue('verify_email');
                        $notified = 1;
                    } catch (\Exception $e) {
                        $user->save();
                    }
                }
                return redirect()->route('user.verify');
            }
        } catch (\Exception $e) {
        }
    }

    /**     * proceed to user login on phone number     */
    public function proceedToPhoneLogin($req, $domain = ''){
        $user = User::where('phone_number', $req->phone_number)->where('dial_code', $req->dialCode)->where('status', 1)->first();
        if ($user) {
            Auth::login($user);
            $user->is_phone_verified = 1;
            $user->phone_token = NULL;
            $user->phone_token_valid_till = NULL;
            $user->save();
            $userid = $user->id;
            if($req->has('access_token')){
                if($req->access_token){
                    $user_device = UserDevice::where('user_id', $userid)->where('device_token', $req->access_token)->first();
                    if(!$user_device){
                        $user_device = new UserDevice();
                        $user_device->user_id = $userid;
                        $user_device->device_type = 'web';
                        $user_device->device_token = $req->access_token;
                        $user_device->save();
                    }
                }
            }
            $this->checkCookies($userid);
            $user_cart = Cart::where('user_id', $userid)->first();
            if ($user_cart) {
                $unique_identifier_cart = Cart::where('unique_identifier', session()->get('_token'))->first();
                if ($unique_identifier_cart) {
                    $unique_identifier_cart_products = CartProduct::where('cart_id', $unique_identifier_cart->id)->get();
                    foreach ($unique_identifier_cart_products as $unique_identifier_cart_product) {
                        $user_cart_product_detail = CartProduct::where('cart_id', $user_cart->id)->where('product_id', $unique_identifier_cart_product->product_id)->first();
                        if ($user_cart_product_detail) {
                            $user_cart_product_detail->quantity = ($unique_identifier_cart_product->quantity + $user_cart_product_detail->quantity);
                            $user_cart_product_detail->save();
                            $unique_identifier_cart_product->delete();
                        } else {
                            $unique_identifier_cart_product->cart_id = $user_cart->id;
                            $unique_identifier_cart_product->save();
                        }
                    }
                    $unique_identifier_cart->delete();
                }
            } else {
                Cart::where('unique_identifier', session()->get('_token'))->update(['user_id' => $userid, 'created_by' => $userid, 'unique_identifier' => '']);
            }
            $message = __('Logged in successfully');
            $redirect_to = '';
            if(session()->has('url.intended')){
                $redirect_to = session()->get('url.intended');
                session()->forget('url.intended');
            }else{
                $redirect_to = route('user.verify');
            }
            $req->request->add(['is_phone'=>1, 'redirect_to'=>$redirect_to]);
            $response = $req->all();
            return $this->successResponse($response, $message);
        }
        else {
            return $this->errorResponse(__('Invalid phone number'), 404);
        }
    }

    /*** Login user via username ***/
    public function loginViaUsername(Request $request, $domain = ''){
        try{
            $errors = array();

            $phone_regex = '/^[0-9\-\(\)\/\+\s]*$/';
            $email_regex = '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
            $username = $request->username;

            if(preg_match($phone_regex, $username))
            {
                $validator = Validator::make($request->all(), [
                    'username'  => 'required',
                    'dialCode'  => 'required',
                    'countryData'  => 'required'
                ]);

                if($validator->fails()){
                    foreach($validator->errors()->toArray() as $error_key => $error_value){
                        $errors['error'] = __($error_value[0]);
                        return response()->json($errors, 422);
                    }
                }

                $phone_number = preg_replace('/\D+/', '', $username);
                $dialCode = $request->dialCode;
                $fullNumber = $request->full_number;
                // $fullNumberWithoutPlus = str_replace('+', '', $fullNumber);
                // $phone_number = substr($fullNumberWithoutPlus, strlen($dialCode));
                $phoneCode = mt_rand(100000, 999999);
                $sendTime = Carbon::now()->addMinutes(10)->toDateTimeString();
                $request->request->add(['is_phone'=>1, 'phone_number'=>$phone_number, 'phoneCode'=>$phoneCode, 'sendTime'=>$sendTime, 'codeSent'=>0]);

                $user = User::where('dial_code', $dialCode)->where('phone_number', $phone_number)->first();
                if(!$user){
                    // $errors['error'] = __('Your phone number is not registered');
                    // return response()->json($errors, 422);
                    $registerUser = $this->registerViaPhone($request)->getData();
                    if($registerUser->status == 'Success'){
                        $user = $registerUser->data;
                    }else{
                        return $this->errorResponse(__('Invalid data'), 404);
                    }
                }else{
                    $user->phone_token = $phoneCode;
                    $user->phone_token_valid_till = $sendTime;
                    $user->save();
                }

                $prefer = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username',
                            'mail_password', 'mail_encryption', 'mail_from', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();

                if($dialCode == "971"){
                    $to = '+'.$dialCode."0".$phone_number;
                } else {
                    $to = '+'.$dialCode.$phone_number;
                }
                $provider = $prefer->sms_provider;
                $body = "Please enter OTP ".$phoneCode." to verify your account.";
                if(!empty($prefer->sms_key) && !empty($prefer->sms_secret) && !empty($prefer->sms_from)){

                    $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
                    if($send == 1){
                        $request->request->add(['codeSent' => 1]);
                        $message = __('An OTP has been sent to your phone. Please check.');
                        $response = $request->all();
                        unset($response['_token']);
                        return $this->successResponse($response, $message);
                    }else{
                        return $this->errorResponse(__('Something went wrong in sending OTP. We are sorry to for the inconvenience'), 404);
                    }
                }else{
                    return $this->errorResponse(__('Provider service is not configured. Please contact administration'), 404);
                }
            }
            elseif (preg_match($email_regex, $username))
            {
                $validator = Validator::make($request->all(), [
                    'username'  => 'required'
                ]);

                if($validator->fails()){
                    foreach($validator->errors()->toArray() as $error_key => $error_value){
                        $errors['error'] = __($error_value[0]);
                        return response()->json($errors, 422);
                    }
                }
                $username = str_ireplace(' ', '', $username);
                if (Auth::attempt(['email' => $username, 'password' => $request->password, 'status' => 1])) {
                    $userid = Auth::id();
                    if($request->has('access_token')){
                        if($request->access_token){
                            $user_device = UserDevice::where('user_id', $userid)->where('device_token', $request->access_token)->first();
                            if(!$user_device){
                                $user_device = new UserDevice();
                                $user_device->user_id = $userid;
                                $user_device->device_type = 'web';
                                $user_device->device_token = $request->access_token;
                                $user_device->save();
                            }
                        }
                    }
                    $this->checkCookies($userid);
                    $user_cart = Cart::where('user_id', $userid)->first();
                    if ($user_cart) {
                        $unique_identifier_cart = Cart::where('unique_identifier', session()->get('_token'))->first();
                        if ($unique_identifier_cart) {
                            $unique_identifier_cart_products = CartProduct::where('cart_id', $unique_identifier_cart->id)->get();
                            foreach ($unique_identifier_cart_products as $unique_identifier_cart_product) {
                                $user_cart_product_detail = CartProduct::where('cart_id', $user_cart->id)->where('product_id', $unique_identifier_cart_product->product_id)->first();
                                if ($user_cart_product_detail) {
                                    $user_cart_product_detail->quantity = ($unique_identifier_cart_product->quantity + $user_cart_product_detail->quantity);
                                    $user_cart_product_detail->save();
                                    $unique_identifier_cart_product->delete();
                                } else {
                                    $unique_identifier_cart_product->cart_id = $user_cart->id;
                                    $unique_identifier_cart_product->save();
                                }
                            }
                            $unique_identifier_cart->delete();
                        }
                    } else {
                        Cart::where('unique_identifier', session()->get('_token'))->update(['user_id' => $userid, 'created_by' => $userid, 'unique_identifier' => '']);
                    }
                    $message = 'Logged in successfully';
                    $redirect_to = '';
                    if(session()->has('url.intended')){
                        $redirect_to = session()->get('url.intended');
                        session()->forget('url.intended');
                    }else{
                        $redirect_to = route('user.verify');
                    }
                    $request->request->add(['is_email'=>1, 'redirect_to'=>$redirect_to]);
                    $response = $request->all();
                    return $this->successResponse($response, $message);
                }
                $checkEmail = User::where('email', $username)->first();
                if ($checkEmail) {
                    if($checkEmail->status != 1){
                        return $this->errorResponse(__('You are unauthorized to access this account.'), 404);
                    }else{
                        return $this->errorResponse(__('Invalid Credentials'), 404);
                    }
                }
                return $this->errorResponse(__('You are not registered with us. Please sign up.'), 404);
            }
            else {
                return $this->errorResponse(__('Invalid email or phone number'), 404);
            }
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Verify Login user via Phone number and create token
     *
     */
    public function verifyPhoneLoginOtp(Request $request, $domain = ''){
        try {
            $username = $request->username;
            $dialCode = $request->dialCode;
            $phone_number = preg_replace('/\D+/', '', $username);
            $user = User::where('dial_code', $dialCode)->where('phone_number', $phone_number)->first();
            if(!$user){
                $errors['error'] = __('Your phone number is not registered');
                return response()->json($errors, 422);
            }
            $currentTime = Carbon::now()->toDateTimeString();
            $message = 'Account verified successfully.';

            if($user->phone_token != $request->verifyToken){
                return $this->errorResponse(__('OTP is not valid'), 404);
            }
            if($currentTime > $user->phone_token_valid_till){
                return $this->errorResponse(__('OTP has been expired.'), 404);
            }
            $request->request->add(['phone_number'=>$phone_number]);
            return $this->proceedToPhoneLogin($request);
        }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    /*** register user via phone number ***/
    public function registerViaPhone($req, $domain = ''){
        try {
            $user = new User();
            $country = Country::where('code', strtoupper($req->countryData))->first();
            // $emailCode = mt_rand(100000, 999999);
            $email = 'ro_'.Carbon::now()->timestamp . '.' . uniqid() . '@royoorders.com';
            $user->type = 1;
            $user->status = 1;
            $user->role_id = 1;
            $user->name = 'RO'.substr($req->phone_number, -6);
            $user->email = $email; //$req->email;
            $user->is_email_verified = 0;
            $user->is_phone_verified = 0;
            $user->country_id = $country->id;
            $user->phone_token = $req->phoneCode;
            $user->dial_code = $req->dialCode;
            // $user->email_token = $emailCode;
            $user->phone_number = $req->phone_number;
            $user->phone_token_valid_till = $req->sendTime;
            // $user->email_token_valid_till = $sendTime;
            // $user->password = Hash::make($req->password);
            $user->save();

            $wallet = $user->wallet;
            $userRefferal = new UserRefferal();
            $userRefferal->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
            if ($req->refferal_code != null) {
                $userRefferal->reffered_by = $req->refferal_code;
            }
            $userRefferal->user_id = $user->id;
            $userRefferal->save();
            if ($user->id > 0) {
                if ($req->refferal_code != null) {
                    $refferal_amounts = ClientPreference::first();
                    if ($refferal_amounts) {
                        if ($refferal_amounts->reffered_by_amount != null && $refferal_amounts->reffered_to_amount != null) {
                            $reffered_by = UserRefferal::where('refferal_code', $req->refferal_code)->first();
                            $user_refferd_by = $reffered_by->user_id;
                            $user_refferd_by = User::where('id', $reffered_by->user_id)->first();
                            if ($user_refferd_by) {
                                //user reffered by amount
                                $wallet_user_reffered_by = $user_refferd_by->wallet;
                                $wallet_user_reffered_by->depositFloat($refferal_amounts->reffered_by_amount, ['Referral code used by <b>' . $req->phone_number . '</b>']);
                                $wallet_user_reffered_by->balance;
                                //user reffered to amount
                                $wallet->depositFloat($refferal_amounts->reffered_to_amount, ['You used referral code of <b>' . $user_refferd_by->name . '</b>']);
                                $wallet->balance;
                            }
                        }
                    }
                }
                Session::forget('referrer');
            }

            return $this->successResponse($user , 'Successfully registered');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function postVendorregister(Request $request){
        $vendor_registration_documents = VendorRegistrationDocument::with('primary')->get();
        if (empty($request->input('user_id'))) {
            if ($vendor_registration_documents->count() > 0) {
                $request->validate(
                    [
                        'address' => 'required|string',
                        'full_name' => 'required|regex:/^[a-zA-Z\s]+$/',
                        'email' => 'required|email|unique:users',
                        'vendor_registration_document.*.did_visit' => 'required',
                        'password'      => [
                            'required',
                            'max:50',
                            Password::min(8)
                                ->numbers()
                                ->letters()
                        ],
                        'confirm_password' => 'required|same:password',
                        'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:150|unique:vendors',
                        'phone_number' => 'required|regex:/^5[0-9]{8}$/|unique:users',
                        'check_conditions' => 'required',
                        'categories' => 'required|array', // Ensure categories is an array
                    ],
                    ['check_conditions.required' => __('Please indicate that you have read and agree to the Terms and Conditions and Privacy Policy')]
                );
            } else {
                $request->validate(
                    [
                        'address' => 'required|string',
                        'full_name' => 'required|regex:/^[a-zA-Z\s]+$/',
                        'email' => 'required|email|unique:users',
                        'password'      => [
                            'required',
                            'max:50',
                            Password::min(8)
                                ->numbers()
                                ->letters()
                        ],
                        'confirm_password' => 'required|same:password',
                        'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:150|unique:vendors',
                        'phone_number' => 'required|regex:/^5[0-9]{8}$/|unique:users',
                        'check_conditions' => 'required',
                        'categories' => 'required|array', // Ensure categories is an array
                    ],
                    ['check_conditions.required' => __('Please indicate that you have read and agree to the Terms and Conditions and Privacy Policy')]
                );
            }
        } else {
            $rules_array = [
                'address' => 'required|string',
                'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:150|unique:vendors',
                'check_conditions' => 'required',
                'categories' => 'required|array', // Ensure categories is an array
            ];
            foreach ($vendor_registration_documents as $vendor_registration_document) {
                $rules_array[$vendor_registration_document->primary->slug] = 'required';
            }
            $request->validate(
                $rules_array,
                ['check_conditions.required' => __('Please indicate that you have read and agree to the Terms and Conditions and Privacy Policy')]
            );
        }
        try {
            DB::beginTransaction();
            $client_detail = Client::first();
            $prefer = ClientPreference::first();
            if(!$request->user_id){
                $user = new User();
                $county = Country::where('code', strtoupper($request->countryData))->first();
                $sendTime = Carbon::now()->addMinutes(10)->toDateTimeString();
                $user->type = 1;
                $user->status = 1;
                $user->role_id = 1;
                $user->is_admin = 1;
                $user->is_email_verified = 0;
                $user->is_phone_verified = 0;
                $user->name = $request->full_name;
                $user->email = $request->email;
                $user->title = $request->title;
                $user->country_id = $county->id;
                $user->dial_code = $request->dialCode;
                $user->phone_token_valid_till = $sendTime;
                $user->email_token_valid_till = $sendTime;
                $user->email_token = mt_rand(100000, 999999);
                $user->phone_token = mt_rand(100000, 999999);
                $user->phone_number = $request->phone_number;
                $user->password = Hash::make($request->password);
                $user->save();
                $wallet = $user->wallet;
            }else{
                $user = User::where('id', $request->user_id)->first();
                $user->title = $request->title;
                $user->save();
            }
            $vendor = new Vendor();
            $count = 0;
            if($prefer){
                if($prefer->dinein_check == 1){$count++;}
                if($prefer->takeaway_check == 1){$count++;}
                if($prefer->delivery_check == 1){$count++;}
            }
            if($count > 1){
                $vendor->dine_in = ($request->has('dine_in') && $request->dine_in == 'on') ? 1 : 0;
                $vendor->takeaway = ($request->has('takeaway') && $request->takeaway == 'on') ? 1 : 0;
                $vendor->delivery = ($request->has('delivery') && $request->delivery == 'on') ? 1 : 0;
            }
            else{
                $vendor->dine_in = $prefer->dinein_check == 1 ? 1 : 0;
                $vendor->takeaway = $prefer->takeaway_check == 1 ? 1 : 0;
                $vendor->delivery = $prefer->delivery_check == 1 ? 1 : 0;
            }
            $vendor->logo = 'default/default_logo.png';
            $vendor->banner = 'default/default_image.png';
            if ($request->hasFile('upload_logo')) {
                $file = $request->file('upload_logo');
                $vendor->logo = Storage::disk('s3')->put('/vendor', $file, 'public');
            }
            if ($request->hasFile('upload_banner')) {
                $file = $request->file('upload_banner');
                $vendor->banner = Storage::disk('s3')->put('/vendor', $file, 'public');
            }
            $vendor->status = 0;
            $vendor->name = $request->name;
            $vendor->email = $request->email;
            $vendor->phone_no = $user->phone_number;
            $vendor->address = $request->address;
            $vendor->website = $request->website;
            $vendor->latitude = $request->latitude;
            $vendor->longitude = $request->longitude;
            $vendor->desc = $request->vendor_description;
            $vendor->slug = Str::slug($request->name, "-");
            $vendor->save();
            
            // Retrieve the selected categories
            $selectedCategories = $request->input('categories');
            foreach ($selectedCategories as $categoryId) {
                VendorCategory::create(['vendor_id' => $vendor->id, 'category_id' => $categoryId, 'status' => 0]);
            }
            $permission_details = Permissions::whereIn('id', [1,2,3,12,17,18,19,20,21])->get();
            if($vendor_registration_documents->count() > 0){
                foreach ($vendor_registration_documents as $vendor_registration_document) {
                    $vendor_registration_document_id = $vendor_registration_document->id;
                    $name = $vendor_registration_document->primary->slug;
                    $vendor_registration_document = $request->$name;
                    $vendor_docs =  new VendorDocs();
                    $vendor_docs->vendor_id = $vendor->id;
                    $vendor_docs->vendor_registration_document_id = $vendor_registration_document_id;
                    $vendor_docs->file_name = Storage::disk('s3')->put('/vendor', $vendor_registration_document, 'public');
                    $vendor_docs->save();
                }
            }
            UserVendor::create(['user_id' => $user->id, 'vendor_id' => $vendor->id]);
            foreach ($permission_details as $permission_detail) {
                UserPermissions::create(['user_id' => $user->id, 'permission_id' => $permission_detail->id]);
            }
            DB::commit();

            if (!empty($prefer->mail_driver) && !empty($prefer->mail_host) && !empty($prefer->mail_port) && !empty($prefer->mail_port) && !empty($prefer->mail_password) && !empty($prefer->mail_encryption)) {
                $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
                $confirured = $this->setMailDetail($prefer->mail_driver, $prefer->mail_host, $prefer->mail_port, $prefer->mail_username, $prefer->mail_password, $prefer->mail_encryption);
                $client_name = $client->name;
                $mail_from = $prefer->mail_from;
                $sendto = $user->email;
                try {
                    $email_template_content = '';
                    $email_template = EmailTemplate::where('id', 1)->first();
                    $returnHTML = view('email.vendorDetails')->with(['vendor_name' => $vendor->name,'email' => $user->email,'phone_no' => $user->phone_number,'address' => $vendor->address])->render();
                    if ($email_template) {
                        $email_template_content = $email_template->content;
                        $email_template_content = str_ireplace("{user_name}", $user->name, $email_template_content);
                        $email_template_content = str_ireplace("{vendor_details}", $returnHTML, $email_template_content);
                        $email_template_content = str_ireplace("{vendor-registration-email-banner}", asset("images/email/vendor-registration-email-banner.jpg"), $email_template_content);
                    }
                    $data = [
                        'email' => $sendto,
                        'mail_from' => $mail_from,
                        'client_name' => $client_name,
                        'subject' => $email_template->subject,
                        'android_app_link' => $prefer->android_app_link,
                        'ios_link' => $prefer->ios_link,
                        'email_template_content' => $email_template_content,
                    ];
                    $admin_data = [
                        'email' => $client_detail->contact_email,
                        'mail_from' => $mail_from,
                        'client_name' => $client_name,
                        'subject' => 'New Vendor Registration',
                        'android_app_link' => $prefer->android_app_link,
                        'ios_link' => $prefer->ios_link,
                        'email_template_content' => $email_template_content,
                    ];
                    dispatch(new \App\Jobs\sendEmailJob($data))->onQueue('verify_email');
                    dispatch(new \App\Jobs\sendEmailJob($admin_data))->onQueue('verify_email');
                } catch (\Exception $e) {
                    $user->save();
                    Log::error('Email failed!');
                    Log::error($e->getMessage());
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Vendor Registration Created Successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function logout(){
        Auth::logout();
        if (!empty(Session::get('current_fcm_token'))) {
            UserDevice::where('device_token', Session::get('current_fcm_token'))->delete();
            Session::forget('current_fcm_token');
        }
        return redirect()->route('customer.login');
    }
}

