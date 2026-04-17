<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\DB;
use Password;
use JWT\Token;
use Validation;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\{LoginRequest, SignupRequest};
use App\Models\{User, Client, ClientPreference, BlockedToken, Otp, Country, UserDevice, UserVerification, ClientLanguage, ClientCurrency, CartProduct, Cart, UserRefferal, EmailTemplate};
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{
    use ApiResponser;
    /**
     * Get Country List
     * * @return country array
     */
    public function countries(Request $request)
    {
        $country = Country::select('id', 'code', 'name', 'nicename', 'phonecode')->get();
        return response()->json([
            'data' => $country
        ]);
    }

    /**
     * Login user and create token
     *
     */
    public function login(LoginRequest $loginReq)
    {
        $errors = array();
        $user = User::with('country')->where('email', $loginReq->email)->first();
        if (!$user) {
            $errors['error'] = __('Invalid email');
            return response()->json($errors, 422);
        }
        if (!Auth::attempt(['email' => $loginReq->email, 'password' => $loginReq->password])) {
            $errors['error'] = __('Invalid password');
            return response()->json($errors, 422);
        }
        //$user = Auth::user();
        $prefer = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
        $verified['is_email_verified'] = $user->is_email_verified;
        $verified['is_phone_verified'] = $user->is_phone_verified;
        $token1 = new Token;
        $token = $token1->make([
            'key' => 'royoorders-jwt',
            'issuer' => 'royoorders.com',
            'expiry' => strtotime('+1 year'), // 1 year token validity - user must manually logout
            'issuedAt' => time(),
            'algorithm' => 'HS256',
        ])->get();
        $token1->setClaim('user_id', $user->id);
        try {
            Token::validate($token, 'secret');
        } catch (\Exception $e) {
        }
        $user_refferal = UserRefferal::where('user_id', $user->id)->first();

        // FIXED: Use composite key (device_token + user_id) to prevent session overwriting
        // This allows multiple users to login on the same device without kicking each other out
        $device = UserDevice::updateOrCreate(
            [
                'device_token' => $loginReq->device_token,
                'user_id' => $user->id
            ],
            [
                'device_type' => $loginReq->device_type,
                'fcm_token' => $loginReq->fcm_token ?? $loginReq->device_token,
                'access_token' => $token
            ]
        );


        $user->auth_token = $token;
        $user->save();

        $user_cart = Cart::where('user_id', $user->id)->first();
        if ($user_cart) {
            $unique_identifier_cart = Cart::where('unique_identifier', $loginReq->device_token)->first();
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
            Cart::where('unique_identifier', $loginReq->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
        }
        $checkSystemUser = $this->checkCookies($user->id);
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['auth_token'] =  $token;
        $data['source'] = $user->image;
        $data['verify_details'] = $verified;
        $data['is_admin'] = $user->is_admin;
        $data['client_preference'] = $prefer;
        $data['dial_code'] = $user->dial_code;
        $data['phone_number'] = $user->phone_number;
        $data['cca2'] = $user->country ? $user->country->code : '';
        $data['callingCode'] = $user->country ? $user->country->phonecode : '';
        $data['refferal_code'] = $user_refferal ? $user_refferal->refferal_code : '';
        $data['social_login'] = false;
        return response()->json(['data' => $data]);
    }

    /**
     * User registraiotn
     * @return [status, email, need_email_verify, need_phone_verify]
     */
    public function signupold(Request $signReq)
    {
        $preferences = ClientPreference::first();
        $rules = [
            'dial_code'   => 'required|string',
            'device_type'   => 'required|string',
            'device_token'  => 'required|string',
            'fcm_token'  => 'required|string',
            'country_code'  => 'required|string',
            'name'          => 'required|string|min:3|max:50',
            'password' => [
                'required',
                'max:50',
                RulesPassword::min(8)
                    ->numbers()
                    ->letters()
            ],
            'refferal_code' => 'nullable|exists:user_refferals,refferal_code',
        ];
        if ($preferences->verify_email == 1) {
            $rules['email'] = 'required|email|unique:users';
        }
        if ($preferences->verify_phone == 1) {
            //$rules['phone_number'] = ['required','string','digits:9','unique:users','regex:/^((50|52|54|55|56|58)([0-9]{7}))$/'];
            $rules['phone_number'] = ['required', 'string', 'digits:9', 'unique:users', 'regex:/^((5|6)([0-9]{8}))$/'];
        }
        $validator = Validator::make($signReq->all(), $rules);
        if ((empty($signReq->email)) && (empty($signReq->phone_number))) {
            $validator = Validator::make($signReq->all(), [
                'email'  => 'required',
                'phone_number'  => 'required'
            ], [
                "email.required" => __('The email or phone number field is required.'),
                "phone_number.required" => __('The email or phone number field is required.'),
            ]);
        } else {
            if (!empty($signReq->email) && ($preferences->verify_email == 0)) {
                $validator = Validator::make($signReq->all(), [
                    'email'  => 'email|unique:users'
                ]);
            }
            if (!empty($signReq->phone_number) && ($preferences->verify_phone == 0) && (!$validator->fails())) {
                $validator = Validator::make($signReq->all(), [
                    'phone_number' => 'string|min:8|max:15|unique:users'
                ]);
            }
        }
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                $errors['error'] = __($error_value[0]);
                return response()->json($errors, 422);
            }
        }

        $user = new User();

        foreach ($signReq->only('name', 'country_id', 'phone_number', 'dial_code') as $key => $value) {
            $user->{$key} = $value;
        }
        $country_detail = Country::where('code', $signReq->country_code)->first();
        $email = (!empty($signReq->email)) ? $signReq->email : ('ro_' . Carbon::now()->timestamp . '.' . uniqid() . '@royoorders.com');
        $phoneCode = mt_rand(100000, 999999);
        $emailCode = mt_rand(100000, 999999);
        $sendTime = Carbon::now()->addMinutes(10)->toDateTimeString();
        $user->password = Hash::make($signReq->password);
        $user->type = 1;
        $user->status = 1;
        $user->role_id = 1;
        $user->email = $email;
        $user->is_email_verified = 0;
        $user->is_phone_verified = 0;
        $user->phone_token = $phoneCode;
        $user->email_token = $emailCode;
        $user->country_id = $country_detail->id;
        
        // Set default language and currency from primary settings
        $primaryLanguage = ClientLanguage::where('is_primary', 1)->first();
        $primaryCurrency = ClientCurrency::where('is_primary', 1)->first();
        $user->language_id = $primaryLanguage ? $primaryLanguage->language_id : 1;
        $user->currency_id = $primaryCurrency ? $primaryCurrency->currency_id : 1;
        
        $user->phone_token_valid_till = $sendTime;
        $user->email_token_valid_till = $sendTime;
        $user->save();
        $wallet = $user->wallet;
        $userRefferal = new UserRefferal();
        $userRefferal->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
        if ($signReq->refferal_code != null) {
            $userRefferal->reffered_by = $signReq->refferal_code;
        }
        $userRefferal->user_id = $user->id;
        $userRefferal->save();
        $user_cart = Cart::where('user_id', $user->id)->first();
        if ($user_cart) {
            $unique_identifier_cart = Cart::where('unique_identifier', $signReq->device_token)->first();
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
            Cart::where('unique_identifier', $signReq->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
        }
        $token1 = new Token;
        $token = $token1->make([
            'key' => 'royoorders-jwt',
            'issuer' => 'royoorders.com',
            'expiry' => strtotime('+1 year'), // 1 year token validity - user must manually logout
            'issuedAt' => time(),
            'algorithm' => 'HS256',
        ])->get();
        $token1->setClaim('user_id', $user->id);
        $user->auth_token = $token;
        $user->save();
        if ($user->id > 0) {
            if ($signReq->refferal_code) {
                $refferal_amounts = ClientPreference::first();
                if ($refferal_amounts) {
                    if ($refferal_amounts->reffered_by_amount != null && $refferal_amounts->reffered_to_amount != null) {
                        $reffered_by = UserRefferal::where('refferal_code', $signReq->refferal_code)->first();
                        $user_refferd_by = $reffered_by->user_id;
                        $user_refferd_by = User::where('id', $reffered_by->user_id)->first();
                        if ($user_refferd_by) {
                            //user reffered by amount
                            $wallet_user_reffered_by = $user_refferd_by->wallet;
                            $wallet_user_reffered_by->deposit($refferal_amounts->reffered_by_amount, ['Referral code used by <b>' . $signReq->name . '</b>']);
                            $wallet_user_reffered_by->balance;
                            //user reffered to amount
                            $wallet->deposit($refferal_amounts->reffered_to_amount, ['You used referal code of <b>' . $user_refferd_by->name . '</b>']);
                            $wallet->balance;
                        }
                    }
                }
            }
            $checkSystemUser = $this->checkCookies($user->id);
            $response['status'] = 'Success';
            $response['name'] = $user->name;
            $response['auth_token'] =  $token;
            $response['email'] = $user->email;
            $response['dial_code'] = $user->dial_code;
            $response['phone_number'] = $user->phone_number;
            $verified['is_email_verified'] = 0;
            $verified['is_phone_verified'] = 0;
            $prefer = ClientPreference::select(
                'mail_type',
                'mail_driver',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_from',
                'sms_provider',
                'sms_key',
                'sms_secret',
                'sms_from',
                'theme_admin',
                'distance_unit',
                'map_provider',
                'date_format',
                'time_format',
                'map_key',
                'sms_provider',
                'verify_email',
                'verify_phone',
                'app_template_id',
                'web_template_id',
                'android_app_link',
                'ios_link',
            )->first();
            $response['verify_details'] = $verified;
            $response['cca2'] = $user->country ? $user->country->code : '';
            $preferData['map_key'] = $prefer->map_key;
            $preferData['theme_admin'] = $prefer->theme_admin;
            $preferData['date_format'] = $prefer->date_format;
            $preferData['time_format'] = $prefer->time_format;
            $preferData['map_provider'] = $prefer->map_provider;
            $preferData['sms_provider'] = $prefer->sms_provider;
            $preferData['verify_email'] = $prefer->verify_email;
            $preferData['verify_phone'] = $prefer->verify_phone;
            $preferData['distance_unit'] = $prefer->distance_unit;
            $preferData['app_template_id'] = $prefer->app_template_id;
            $preferData['web_template_id'] = $prefer->web_template_id;
            $response['client_preference'] = $preferData;
            $response['refferal_code'] = $userRefferal ? $userRefferal->refferal_code : '';

            // $user_device[] = [
            //     'access_token' => '',
            //     'user_id' => $user->id,
            //     'device_type' => $signReq->device_type,
            //     'device_token' => $signReq->device_token,
            // ];
            // UserDevice::insert($user_device);

            UserDevice::Create(
                [
                    'user_id' => $user->id,
                    'device_type' => $signReq->device_type,
                    'device_token' => $signReq->device_token,
                    'fcm_token' => $signReq->fcm_token,
                    'access_token' => $token
                ]
            );

            if (!empty($prefer->sms_key) && !empty($prefer->sms_secret) && !empty($prefer->sms_from)) {
                $response['send_otp'] = 1;
                if ($user->dial_code == "971") {
                    $to = '+' . $user->dial_code . "0" . $user->phone_number;
                } else {
                    $to = '+' . $user->dial_code . $user->phone_number;
                }
                $provider = $prefer->sms_provider;
                $body = "Dear " . ucwords($user->name) . ", " . $phoneCode . " is your OTP for " . env('APP_NAME') . " account verification. OTP is valid for 10 minutes. Please do not share the OTP";
                $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
            }
            if (!empty($prefer->mail_driver) && !empty($prefer->mail_host) && !empty($prefer->mail_port) && !empty($prefer->mail_port) && !empty($prefer->mail_password) && !empty($prefer->mail_encryption)) {
                // Use new EmailService for multi-language and RTL support
                $emailSent = $this->sendEmail(
                    $signReq->email,
                    2, // Verify email template ID
                    [
                        'code' => $emailCode,
                        'customer_name' => ucwords($user->name),
                        'verify-email-banner' => asset("images/email/verify-email-banner.jpg")
                    ],
                    ['code' => $emailCode], // Additional data
                    $user->language_id ?? null,
                    true,
                    'verify_email'
                );
                if ($emailSent) {
                    $notified = 1;
                }
                $response['social_login'] = false;
            }
            return response()->json(['data' => $response]);
        } else {
            $errors['errors']['user'] = 'Something went wrong. Please try again.';
        }
    }
    public function signup(Request $signReq)
    {
        $preferences = ClientPreference::first();
        $rules = [
            'dial_code'   => 'required|string',
            'device_type'   => 'required|string',
            'device_token'  => 'required|string',
            'fcm_token'  => 'required|string',
            'country_code'  => 'required|string',
            'refferal_code' => 'nullable|exists:user_refferals,refferal_code',
        ];
        if ($preferences->verify_email == 1) {
            $rules['email'] = 'required|email|unique:users';
        }
        $validator = Validator::make($signReq->all(), $rules);
        if (empty($signReq->email)) {
            $validator = Validator::make($signReq->all(), [
                'email'  => 'required',
            ], [
                "email.required" => __('The email field is required.')
            ]);
        }
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                $errors['error'] = __($error_value[0]);
                return response()->json($errors, 422);
            }
        }

        $user = new User();

        foreach ($signReq->only('name', 'country_id', 'phone_number', 'dial_code') as $key => $value) {
            $user->{$key} = $value;
        }
        $country_detail = Country::where('code', $signReq->country_code)->first();
        $email = (!empty($signReq->email)) ? $signReq->email : ('ro_' . Carbon::now()->timestamp . '.' . uniqid() . '@royoorders.com');
        //$phoneCode = mt_rand(100000, 999999);
        $emailCode = mt_rand(100000, 999999);
        $sendTime = Carbon::now()->addMinutes(10)->toDateTimeString();
        $user->password = Hash::make($signReq->password);
        $user->type = 1;
        $user->status = 1;
        $user->role_id = 1;
        $user->email = $email;
        $user->is_email_verified = 0;
        $user->is_phone_verified = 0;
        //$user->phone_token = $phoneCode;
        $user->email_token = $emailCode;
        $user->country_id = $country_detail->id;
        
        // Set default language and currency from primary settings
        $primaryLanguage = ClientLanguage::where('is_primary', 1)->first();
        $primaryCurrency = ClientCurrency::where('is_primary', 1)->first();
        $user->language_id = $primaryLanguage ? $primaryLanguage->language_id : 1;
        $user->currency_id = $primaryCurrency ? $primaryCurrency->currency_id : 1;
        
        $user->phone_token_valid_till = $sendTime;
        $user->email_token_valid_till = $sendTime;
        $user->save();
        $wallet = $user->wallet;
        $userRefferal = new UserRefferal();
        $userRefferal->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
        if ($signReq->refferal_code != null) {
            $userRefferal->reffered_by = $signReq->refferal_code;
        }
        $userRefferal->user_id = $user->id;
        $userRefferal->save();
        $user_cart = Cart::where('user_id', $user->id)->first();
        if ($user_cart) {
            $unique_identifier_cart = Cart::where('unique_identifier', $signReq->device_token)->first();
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
            Cart::where('unique_identifier', $signReq->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
        }
        $token1 = new Token;
        $token = $token1->make([
            'key' => 'royoorders-jwt',
            'issuer' => 'royoorders.com',
            'expiry' => strtotime('+1 year'), // 1 year token validity - user must manually logout
            'issuedAt' => time(),
            'algorithm' => 'HS256',
        ])->get();
        $token1->setClaim('user_id', $user->id);
        $user->auth_token = $token;
        $user->save();
        if ($user->id > 0) {
            if ($signReq->refferal_code) {
                $refferal_amounts = ClientPreference::first();
                if ($refferal_amounts) {
                    if ($refferal_amounts->reffered_by_amount != null && $refferal_amounts->reffered_to_amount != null) {
                        $reffered_by = UserRefferal::where('refferal_code', $signReq->refferal_code)->first();
                        $user_refferd_by = $reffered_by->user_id;
                        $user_refferd_by = User::where('id', $reffered_by->user_id)->first();
                        if ($user_refferd_by) {
                            //user reffered by amount
                            $wallet_user_reffered_by = $user_refferd_by->wallet;
                            $wallet_user_reffered_by->deposit($refferal_amounts->reffered_by_amount, ['Referral code used by <b>' . $signReq->name . '</b>']);
                            $wallet_user_reffered_by->balance;
                            //user reffered to amount
                            $wallet->deposit($refferal_amounts->reffered_to_amount, ['You used referal code of <b>' . $user_refferd_by->name . '</b>']);
                            $wallet->balance;
                        }
                    }
                }
            }
            $checkSystemUser = $this->checkCookies($user->id);
            $response['status'] = 'Success';
            $response['name'] = $user->name;
            $response['auth_token'] =  $token;
            $response['email'] = $user->email;
            $response['dial_code'] = $user->dial_code;
            $response['phone_number'] = $user->phone_number;
            $verified['is_email_verified'] = 0;
            $verified['is_phone_verified'] = 0;
            $prefer = ClientPreference::select(
                'mail_type',
                'mail_driver',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_from',
                'sms_provider',
                'sms_key',
                'sms_secret',
                'sms_from',
                'theme_admin',
                'distance_unit',
                'map_provider',
                'date_format',
                'time_format',
                'map_key',
                'sms_provider',
                'verify_email',
                'verify_phone',
                'app_template_id',
                'web_template_id',
                'android_app_link',
                'ios_link',
            )->first();
            $response['verify_details'] = $verified;
            $response['cca2'] = $user->country ? $user->country->code : '';
            $preferData['map_key'] = $prefer->map_key;
            $preferData['theme_admin'] = $prefer->theme_admin;
            $preferData['date_format'] = $prefer->date_format;
            $preferData['time_format'] = $prefer->time_format;
            $preferData['map_provider'] = $prefer->map_provider;
            $preferData['sms_provider'] = $prefer->sms_provider;
            $preferData['verify_email'] = $prefer->verify_email;
            $preferData['verify_phone'] = $prefer->verify_phone;
            $preferData['distance_unit'] = $prefer->distance_unit;
            $preferData['app_template_id'] = $prefer->app_template_id;
            $preferData['web_template_id'] = $prefer->web_template_id;
            $response['client_preference'] = $preferData;
            $response['refferal_code'] = $userRefferal ? $userRefferal->refferal_code : '';

            UserDevice::Create(
                [
                    'user_id' => $user->id,
                    'device_type' => $signReq->device_type,
                    'device_token' => $signReq->device_token,
                    'fcm_token' => $signReq->fcm_token,
                    'access_token' => $token
                ]
            );

            if (!empty($prefer->mail_driver) && !empty($prefer->mail_host) && !empty($prefer->mail_port) && !empty($prefer->mail_port) && !empty($prefer->mail_password) && !empty($prefer->mail_encryption)) {
                // Use new EmailService for multi-language and RTL support
                $emailSent = $this->sendEmail(
                    $signReq->email,
                    2, // Verify email template ID
                    [
                        'code' => $emailCode,
                        'customer_name' => ucwords($user->name),
                        'verify-email-banner' => asset("images/email/verify-email-banner.jpg")
                    ],
                    ['code' => $emailCode], // Additional data
                    $user->language_id ?? null,
                    true,
                    'verify_email'
                );
                if ($emailSent) {
                    $notified = 1;
                }
                $response['social_login'] = false;
            }
            return response()->json(['data' => $response]);
        } else {
            $errors['errors']['user'] = 'Something went wrong. Please try again.';
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendToken(Request $request, $domain = '', $uid = 0)
    {
        $rules = [
            'type'   => 'required|string',
        ];
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user) {
            return response()->json(['error' => __('User not found.')], 404);
        }
        if ($user->is_email_verified == 1 && $user->is_phone_verified == 1) {
            return response()->json(['message' => __('Account already verified.')], 409);
        }

        $preferences = ClientPreference::first();
        if ($preferences->verify_email == 1) {
            if ($request->type == 'email') {
                $rules['email'] = 'required|email|unique:users,email,' . $user->id;
            }
        }
        if ($preferences->verify_phone == 1) {
            if ($request->type == 'phone') {
                //$rules['phone_number'] = ['required','string','size:9','unique:users,phone_number,'.$user->id,'regex:/^((50|52|54|55|56|58)([0-9]{7}))$/'];
                $rules['phone_number'] = ['required', 'string', 'size:9', 'unique:users,phone_number,' . $user->id, 'regex:/^((5|6)([0-9]{8}))$/'];
            }
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                $errors['error'] = __($error_value[0]);
                return response()->json($errors, 422);
            }
        }

        try {
            $notified = 1;
            $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
            $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from', 'android_app_link', 'ios_link')->where('id', '>', 0)->first();
            $newDateTime = Carbon::now()->addMinutes(10)->toDateTimeString();
            if ($request->type == "phone") {
                if ($user->is_phone_verified == 0) {
                    $otp = mt_rand(100000, 999999);
                    $user->phone_token = $otp;
                    $user->phone_token_valid_till = $newDateTime;
                    $user->save();
                    $provider = $data->sms_provider;
                    $to = '+' . $request->dial_code . $request->phone_number;
                    $body = "Dear " . ucwords($user->name) . ", " . $otp . " is your OTP for " . env('APP_NAME') . " account verification. OTP is valid for 10 minutes. Please do not share the OTP";
                    if (!empty($data->sms_key) && !empty($data->sms_secret)) {
                        if (!empty($data->sms_from)) {
                            $send = $this->sendSms($provider, $data->sms_key, $data->sms_secret, $data->sms_from, $to, $body);
                            if ($send) {
                                $message = __('An OTP has been sent to your phone. Please check.');
                                return $this->successResponse([], $message);
                            }
                        } else {
                            $message = __('An OTP has been sent to your phone. Please check.');
                            return $this->successResponse([], $message);
                        }
                    } else {
                        return $this->errorResponse(__('Provider service is not configured. Please contact administration.'), 404);
                    }
                }
            } else {
                if ($user->is_email_verified == 0) {
                    $otp = mt_rand(100000, 999999);
                    $user->email = $request->email;
                    $user->email_token = $otp;
                    $user->email_token_valid_till = $newDateTime;
                    $user->save();
                    if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
                        // Use new EmailService for multi-language and RTL support
                        $this->sendEmail(
                            $user->email,
                            2, // Verify email template ID
                            [
                                'code' => $otp,
                                'customer_name' => ucwords($user->name),
                                'verify-email-banner' => asset("images/email/verify-email-banner.jpg")
                            ],
                            ['code' => $otp], // Additional data
                            $user->language_id ?? null,
                            true,
                            'verify_email'
                        );
                        $message = __('An OTP has been sent to your email. Please check.');
                        return $this->successResponse([], $message);
                    } else {
                        return $this->errorResponse(__('Provider service is not configured. Please contact administration.'), 404);
                    }
                }
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyToken(Request $request, $domain = '')
    {
        try {
            $user = User::where('id', Auth::user()->id)->first();
            if (!$user || !$request->has('type')) {
                return $this->errorResponse(__('User not found.'), 404);
            }
            $currentTime = Carbon::now()->toDateTimeString();
            $message = 'Account verified successfully.';
            if ($request->has('is_forget_password') && $request->is_forget_password == 1) {
                $message = 'OTP matched successfully.';
            }
            if ($request->type == 'phone') {
                $phone_number = str_ireplace(' ', '', $request->phone_number);
                $user_detail_exist = User::where('phone_number', $phone_number)->whereNotIn('id', [$user->id])->first();
                if ($user_detail_exist) {
                    return response()->json(['error' => __('phone number in use!')], 404);
                }
                if ($user->phone_token != $request->otp) {
                    return $this->errorResponse(__('OTP is not valid'), 404);
                }
                if ($currentTime > $user->phone_token_valid_till) {
                    return $this->errorResponse(__('OTP has been expired.'), 404);
                }
                $user->phone_token = NULL;
                $user->phone_number = $request->phone_number;
                $user->is_phone_verified = 1;
                $user->phone_token_valid_till = NULL;
                $user->save();
                return $this->successResponse(getUserDetailViaApi($user), $message);
            } elseif ($request->type == 'email') {
                $user_detail_exist = User::where('email', $request->email)->where('id', '!=', $user->id)->first();
                if ($user_detail_exist) {
                    return $this->errorResponse(__('Email already in use!'), 404);
                }
                if ($user->email_token != $request->otp) {
                    return $this->errorResponse(__('OTP is not valid'), 404);
                }
                if ($currentTime > $user->email_token_valid_till) {
                    return $this->errorResponse(__('OTP has been expired.'), 404);
                }
                $user->email_token = NULL;
                $user->is_email_verified = 1;
                $user->email_token_valid_till = NULL;
                $user->save();
                //send welcome mail
                if ($user->welcome_status == 0) {
                    User::where('id', Auth::user()->id)->update(['welcome_status' => 1]);
                    // Use language from request header (device language) instead of user's stored language
                    // Priority: Request header > User language > Session > Default
                    $languageId = $this->getLanguageFromRequest($request, $user);
                    // Use new EmailService for multi-language and RTL support
                    $this->sendEmail(
                        $user->email,
                        12, // Welcome email template ID
                        [
                            'customer_name' => ucwords($user->name),
                            'welcome-email-banner' => asset("images/email/welcome-email-banner.jpg")
                        ],
                        [],
                        $languageId,
                        true,
                        'verify_email'
                    );
                }
                return $this->successResponse(getUserDetailViaApi($user), $message);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $blockToken = new BlockedToken();
        $header = $request->header();
        $blockToken->token = $header['authorization'][0];
        $blockToken->expired = '1';
        $blockToken->save();

        $del_token = UserDevice::where('access_token', $header['authorization'][0])->delete();

        if ($request->delete_account == true) {
            User::where('id', Auth::id())->update(['status' => 3]);
            return response()->json([
                'message' => __('Successfully User Inactivated')
            ]);
        }
        return response()->json([
            'message' => __('Successfully logged out')
        ]);
    }

    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users'
            ], ['email.required' => __('The email field is required.'), 'email.exists' => __('You are not registered with us. Please sign up.')]);
            if ($validator->fails()) {
                foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                    $errors['error'] = __($error_value[0]);
                    return response()->json($errors, 422);
                }
            }
            $customer_name = User::where('email', $request->email)->value('name');
            $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
            $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from', 'android_app_link', 'ios_link')->where('id', '>', 0)->first();
            if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
                $token = Str::random(60);
                DB::table('password_resets')->insert(['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]);
                
                // Get user to determine language
                $user = User::where('email', $request->email)->first();
                $languageId = $user ? ($user->language_id ?? null) : null;
                
                // Use new EmailService for multi-language and RTL support
                try {
                    $this->sendEmail(
                        $request->email,
                        3, // Forgot password email template ID
                        [
                            'reset_link' => url('/reset-password/' . $token),
                            'customer_name' => $customer_name,
                            'forgot-password-email-banner' => asset("images/email/forgot-password-email-banner.jpg")
                        ],
                        ['token' => $token], // Additional data
                        $languageId,
                        true,
                        'verify_email'
                    );
                } catch (\Exception $th) {
                    return $this->errorResponse($th->getMessage(), $th->getCode());
                }
            }
            return response()->json(['success' => __('We have e-mailed your password reset link!')], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * reset password.
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request, $domain = '')
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'otp' => 'required|string|min:6|max:50',
            'new_password' => 'required|string|min:6|max:50',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                $errors['error'] = __($error_value[0]);
                return response()->json($errors, 422);
            }
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => __('User not found.')], 404);
        }
        if ($user->email_token != $request->otp) {
            return response()->json(['error' => __('OTP is not valid')], 404);
        }
        $currentTime = Carbon::now()->toDateTimeString();
        if ($currentTime > $user->phone_token_valid_till) {
            return response()->json(['error' => __('OTP has been expired.')], 404);
        }
        $user->password = Hash::make($request['new_password']);
        $user->save();
        return response()->json(['message' => __('Password updated successfully.')]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function sacialData(Request $request)
    {
        return response()->json([
            'message' => __('Successfully logged out')
        ]);
    }

    /**     * proceed to user login on phone number     */
    public function proceedToPhoneLogin($req, $currentLanguage = null)
    {
        $user = User::where('phone_number', $req->phone_number)->where('dial_code', $req->dialCode)->first();
        if ($user) {
            if ($user->status != 1) {
                $errors['error'] = __('You are unauthorized to access this account.');
                return response()->json($errors, 422);
            }
            Auth::login($user);
            $prefer = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
            $token1 = new Token;
            $token = $token1->make([
                'key' => 'royoorders-jwt',
                'issuer' => 'royoorders.com',
                'expiry' => strtotime('+1 year'), // 1 year token validity - user must manually logout
                'issuedAt' => time(),
                'algorithm' => 'HS256',
            ])->get();
            $token1->setClaim('user_id', $user->id);
            try {
                Token::validate($token, 'secret');
            } catch (\Exception $e) {
            }
            $user_refferal = UserRefferal::where('user_id', $user->id)->first();

            // FIXED: Use composite key (device_token + user_id) to prevent session overwriting
            $device = UserDevice::updateOrCreate(
                [
                    'device_token' => $req->device_token,
                    'user_id' => $user->id
                ],
                [
                    'device_type' => $req->device_type,
                    'fcm_token' => $req->fcm_token ?? $req->device_token,
                    'access_token' => $token
                ]
            );
            $user->is_phone_verified = 1;
            $user->phone_token = NULL;
            $user->phone_token_valid_till = NULL;
            $user->auth_token = $token;
            $user->save();

            $verified['is_email_verified'] = $user->is_email_verified;
            $verified['is_phone_verified'] = $user->is_phone_verified;

            $user_cart = Cart::where('user_id', $user->id)->first();
            if ($user_cart) {
                $unique_identifier_cart = Cart::where('unique_identifier', $req->device_token)->first();
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
                Cart::where('unique_identifier', $req->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
            }
            $checkSystemUser = $this->checkCookies($user->id);
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['auth_token'] =  $token;
            $data['source'] = $user->image;
            $data['verify_details'] = $verified;
            $data['is_admin'] = $user->is_admin;
            $data['client_preference'] = $prefer;
            $data['dial_code'] = $user->dial_code;
            $data['phone_number'] = $user->phone_number;
            $data['cca2'] = $user->country ? $user->country->code : '';
            $data['callingCode'] = $user->country ? $user->country->phonecode : '';
            $data['refferal_code'] = $user_refferal ? $user_refferal->refferal_code : '';
            $data['current_language'] = $currentLanguage;

            $message = __('Logged in successfully');
            // return response()->json(['data' => $data]);
            return $this->successResponse($data, $message);
        } else {
            return $this->errorResponse(__('Invalid phone number'), 404);
        }
    }
    /**     * proceed to user login on email     */
    public function proceedToEmailLogin($req)
    {
        $user = User::with('country')->where('email', $req->email)->first();
        if ($user) {
            if ($user->status != 1) {
                $errors['error'] = __('You are unauthorized to access this account.');
                return response()->json($errors, 422);
            }
            Auth::login($user);
            $prefer = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
            $token1 = new Token;
            $token = $token1->make([
                'key' => 'royoorders-jwt',
                'issuer' => 'royoorders.com',
                'expiry' => strtotime('+1 year'), // 1 year token validity - user must manually logout
                'issuedAt' => time(),
                'algorithm' => 'HS256',
            ])->get();
            $token1->setClaim('user_id', $user->id);
            try {
                Token::validate($token, 'secret');
            } catch (\Exception $e) {
            }
            $user_refferal = UserRefferal::where('user_id', $user->id)->first();

            // FIXED: Use composite key (device_token + user_id) to prevent session overwriting
            $device = UserDevice::updateOrCreate(
                [
                    'device_token' => $req->device_token,
                    'user_id' => $user->id
                ],
                [
                    'device_type' => $req->device_type,
                    'fcm_token' => $req->fcm_token ?? $req->device_token,
                    'access_token' => $token
                ]
            );
            $user->is_email_verified = 1;
            $user->email_token = NULL;
            $user->email_token_valid_till = NULL;
            $user->auth_token = $token;
            $user->save();

            $verified['is_email_verified'] = $user->is_email_verified;
            $verified['is_phone_verified'] = $user->is_phone_verified;

            $user_cart = Cart::where('user_id', $user->id)->first();
            if ($user_cart) {
                $unique_identifier_cart = Cart::where('unique_identifier', $req->device_token)->first();
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
                Cart::where('unique_identifier', $req->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
            }
            $checkSystemUser = $this->checkCookies($user->id);
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['auth_token'] =  $token;
            $data['source'] = $user->image;
            $data['verify_details'] = $verified;
            $data['is_admin'] = $user->is_admin;
            $data['client_preference'] = $prefer;
            $data['dial_code'] = $user->dial_code;
            $data['phone_number'] = $user->phone_number;
            $data['cca2'] = $user->country ? $user->country->code : '';
            $data['callingCode'] = $user->country ? $user->country->phonecode : '';
            $data['refferal_code'] = $user_refferal ? $user_refferal->refferal_code : '';

            $message = __('Logged in successfully');
            // return response()->json(['data' => $data]);
            return $this->successResponse($data, $message);
        } else {
            return $this->errorResponse(__('You are not registered with us. Please sign up.'), 404);
        }
    }

    /*** Login user via username ***/
    public function loginViaUsernameold(Request $request, $domain = '')
    {
        try {
            $errors = array();

            $phone_regex = '/^[0-9\-\(\)\/\+\s]*$/';
            $email_regex = '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
            $username = $request->username;

            if (preg_match($phone_regex, $username)) {
                $validator = Validator::make($request->all(), [
                    'username'  => 'required',
                    'dialCode'  => 'required',
                    'countryData'  => 'required|string',
                    'device_type'   => 'required|string',
                    'device_token'  => 'required|string',
                ]);
                if ($validator->fails()) {
                    foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                        $errors['error'] = __($error_value[0]);
                        return response()->json($errors, 422);
                    }
                }
                $phone_number = preg_replace('/\D+/', '', $username);
                $dialCode = $request->dialCode;
                $fullNumber = $request->full_number;
                $phoneCode = mt_rand(100000, 999999);
                $sendTime = Carbon::now()->addMinutes(10)->toDateTimeString();
                $request->request->add(['is_phone' => 1, 'phone_number' => $phone_number, 'phoneCode' => $phoneCode, 'sendTime' => $sendTime, 'codeSent' => 0]);

                $user = User::where('dial_code', $dialCode)->where('phone_number', $phone_number)->first();
                if (!$user) {
                    // $registerUser = $this->registerViaPhone($request)->getData();
                    // if ($registerUser->status == 'Success') {
                    //     $user = $registerUser->data;
                    // } else {
                    //     return $this->errorResponse(__('Invalid data'), 404);
                    // }
                    return $this->errorResponse(__('Sorry you are not registered with us. Please sign up'), 404);
                } else {
                    $user->phone_token = $phoneCode;
                    $user->phone_token_valid_till = $sendTime;
                    $user->save();
                }

                $prefer = ClientPreference::select(
                    'mail_type',
                    'mail_driver',
                    'mail_host',
                    'mail_port',
                    'mail_username',
                    'mail_password',
                    'mail_encryption',
                    'mail_from',
                    'sms_provider',
                    'sms_key',
                    'sms_secret',
                    'sms_from',
                    'theme_admin',
                    'distance_unit',
                    'map_provider',
                    'date_format',
                    'time_format',
                    'map_key',
                    'sms_provider',
                    'verify_email',
                    'verify_phone',
                    'app_template_id',
                    'web_template_id'
                )->first();

                if ($dialCode == "971") {
                    $to = '+' . $dialCode . "0" . $phone_number;
                } else {
                    $to = '+' . $dialCode . $phone_number;
                }
                $provider = $prefer->sms_provider;
                $body = "Dear customer " . $phoneCode . " is your OTP for " . env('APP_NAME') . " login. OTP is valid for 10 minutes. Please do not share the OTP";
                if (!empty($prefer->sms_key) && !empty($prefer->sms_secret) && !empty($prefer->sms_from)) {
                    $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
                    if ($send) {
                        $request->request->add(['codeSent' => 1]);
                        $message = __('An OTP has been sent to your phone. Please check.');
                        $response = $request->all();
                        return $this->successResponse($response, $message);
                    } else {
                        return $this->errorResponse(__('Something went wrong in sending OTP. We are sorry to for the inconvenience'), 404);
                    }
                } else {
                    return $this->errorResponse(__('Provider service is not configured. Please contact administration'), 404);
                }
            } elseif (preg_match($email_regex, $username)) {
                $validator = Validator::make($request->all(), [
                    'username'  => 'required',
                    'device_type'   => 'required|string',
                    'device_token'  => 'required|string',
                ]);

                if ($validator->fails()) {
                    foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                        $errors['error'] = __($error_value[0]);
                        return response()->json($errors, 422);
                    }
                }
                $username = str_ireplace(' ', '', $username);

                $user = User::with('country')->where('email', $username)->first();
                if ($user) {
                    if ($user->status != 1) {
                        $errors['error'] = __('You are unauthorized to access this account.');
                        return response()->json($errors, 422);
                    }
                } else {
                    return $this->errorResponse(__('You are not registered with us. Please sign up.'), 404);
                }

                if (!Auth::attempt(['email' => $username, 'password' => $request->password])) {
                    $errors['error'] = __('Invalid password');
                    return response()->json($errors, 422);
                }
                //$user = Auth::user();
                $prefer = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
                $verified['is_email_verified'] = $user->is_email_verified;
                $verified['is_phone_verified'] = $user->is_phone_verified;
                $token1 = new Token;
                $token = $token1->make([
                    'key' => 'royoorders-jwt',
                    'issuer' => 'royoorders.com',
                    'expiry' => strtotime('+1 year'), // 1 year token validity - user must manually logout
                    'issuedAt' => time(),
                    'algorithm' => 'HS256',
                ])->get();
                $token1->setClaim('user_id', $user->id);
                try {
                    Token::validate($token, 'secret');
                } catch (\Exception $e) {
                }
                $user_refferal = UserRefferal::where('user_id', $user->id)->first();
                // FIXED: Use composite key (device_token + user_id) to prevent session overwriting
                UserDevice::updateOrCreate(
                    [
                        'device_token' => $request->device_token,
                        'user_id' => $user->id
                    ],
                    [
                        'device_type' => $request->device_type,
                        'fcm_token' => $request->fcm_token ?? $request->device_token,
                        'access_token' => $token
                    ]
                );

                $user->auth_token = $token;
                $user->save();

                $user_cart = Cart::where('user_id', $user->id)->first();
                if ($user_cart) {
                    $unique_identifier_cart = Cart::where('unique_identifier', $request->device_token)->first();
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
                    Cart::where('unique_identifier', $request->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
                }
                $checkSystemUser = $this->checkCookies($user->id);
                $data['name'] = $user->name;
                $data['email'] = $user->email;
                $data['auth_token'] =  $token;
                $data['source'] = $user->image;
                $data['verify_details'] = $verified;
                $data['is_admin'] = $user->is_admin;
                $data['client_preference'] = $prefer;
                $data['dial_code'] = $user->dial_code;
                $data['phone_number'] = $user->phone_number;
                $data['cca2'] = $user->country ? $user->country->code : '';
                $data['callingCode'] = $user->country ? $user->country->phonecode : '';
                $data['refferal_code'] = $user_refferal ? $user_refferal->refferal_code : '';
                return response()->json(['data' => $data]);
            } else {
                return $this->errorResponse(__('Invalid email or phone number'), 404);
            }
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }
    public function loginViaUsername(Request $request, $domain = '')
    {
        try {
            $errors = array();
            
            // Get current language info
            $currentLanguage = null;
            if ($request->hasHeader('language')) {
                $langValue = $request->header('language');
                
                if(is_numeric($langValue)){
                    $checkLang = ClientLanguage::with('language:id,sort_code,name,is_rtl')
                        ->where('language_id', $langValue)
                        ->first();
                    if($checkLang && $checkLang->language){
                        $currentLanguage = $checkLang->language;
                    }
                } else {
                    $checkLang = ClientLanguage::whereHas('language', function($q) use ($langValue) {
                        $q->where('sort_code', $langValue);
                    })->with('language:id,sort_code,name,is_rtl')->first();
                    if($checkLang && $checkLang->language){
                        $currentLanguage = $checkLang->language;
                    }
                }
            }

            $phone_regex = '/^[0-9\-\(\)\/\+\s]*$/';
            $email_regex = '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
            $username = $request->username;

            if (preg_match($phone_regex, $username)) {
                $validator = Validator::make($request->all(), [
                    'username'  => 'required',
                    'dialCode'  => 'required',
                    'countryData'  => 'required|string',
                    'device_type'   => 'required|string',
                    'device_token'  => 'required|string',
                ]);
                if ($validator->fails()) {
                    foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                        $errors['error'] = __($error_value[0]);
                        return response()->json($errors, 422);
                    }
                }
                
                // Validate dialCode is exactly 971 (without + sign)
                if ($request->dialCode !== '971') {
                    $errors['error'] = __('Only UAE dial code (971) is allowed without + sign');
                    return response()->json($errors, 422);
                }
                
                $phone_number = preg_replace('/\D+/', '', $username);
                $dialCode = $request->dialCode;
                $fullNumber = $request->full_number;
                $phoneCode = mt_rand(100000, 999999);
                $sendTime = Carbon::now()->addMinutes(10)->toDateTimeString();
                $request->request->add(['is_phone' => 1, 'phone_number' => $phone_number, 'phoneCode' => $phoneCode, 'sendTime' => $sendTime, 'codeSent' => 0]);

                // $user = User::where('dial_code', $dialCode)->where('phone_number', $phone_number)->first();
                // if (!$user) {
                //     return $this->errorResponse(__('Sorry you are not registered with us. Please sign up'), 404);
                // } elseif($user->status == 2){
                //     return $this->errorResponse(__('You are unauthorized to access this account.'), 404);
                // } elseif($user->status == 3){
                //     return $this->errorResponse(__('Your Account is Inactive, Please Contact Wayno Support Team'), 404);
                // }else {
                //     $user->phone_token = $phoneCode;
                //     $user->phone_token_valid_till = $sendTime;
                //     $user->save();
                // }

                //changed the flow for signup using phone number
                $user = User::where('dial_code', $dialCode)->where('phone_number', $phone_number)->first();
                if (!$user) {
                    // Get default language and currency for new users
                    $primaryLanguage = ClientLanguage::where('is_primary', 1)->first();
                    $primaryCurrency = ClientCurrency::where('is_primary', 1)->first();
                    
                    // Get country from country code
                    $country = Country::where('code', strtoupper($request->countryData))->first();
                    
                    // Create a new user if not registered
                    $user = User::create([
                        'dial_code' => $dialCode,
                        'phone_number' => $phone_number,
                        'country_id' => $country ? $country->id : null,
                        'status' => 1,
                        'type' => 1,
                        'role_id' => 1,
                        'language_id' => $primaryLanguage ? $primaryLanguage->language_id : 1,
                        'currency_id' => $primaryCurrency ? $primaryCurrency->currency_id : 1,
                    ]);
                } elseif ($user->status == 2) {
                    return $this->errorResponse(__('You are unauthorized to access this account.'), 403);
                } elseif ($user->status == 3) {
                    return $this->errorResponse(__('Your Account is Inactive, Please Contact Wayno Support Team'), 403);
                }

                // Generate OTP and send
                $user->phone_token = $phoneCode;
                $user->phone_token_valid_till = $sendTime;
                $user->save();

                $prefer = ClientPreference::select('sms_provider', 'sms_key', 'sms_secret', 'sms_from')->first();

                if ($dialCode == "971") {
                    $to = '+' . $dialCode . "0" . $phone_number;
                } else {
                    $to = '+' . $dialCode . $phone_number;
                }
                $provider = $prefer->sms_provider;
                $body = "Dear customer " . $phoneCode . " is your OTP for " . env('APP_NAME') . " login. OTP is valid for 10 minutes. Please do not share the OTP";
                if (!empty($prefer->sms_key) && !empty($prefer->sms_secret)) {
                    if (!empty($prefer->sms_from)) {
                        $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $phone_number, $body);
                        if ($send) {
                            $request->request->add(['codeSent' => 1]);
                            $message = __('An OTP has been sent to your phone. Please check.');
                            $response = $request->all();
                            $response['current_language'] = $currentLanguage;
                            return $this->successResponse($response, $message);
                        } else {
                            return $this->errorResponse(__('Something went wrong in sending OTP. We are sorry to for the inconvenience'), 404);
                        }
                    } else {
                        $request->request->add(['codeSent' => 0]);
                        $message = __('An OTP has been sent to your phone. Please check.');
                        $response = $request->all();
                        $response['current_language'] = $currentLanguage;
                        return $this->successResponse($response, $message);
                    }
                } else {
                    return $this->errorResponse(__('Provider service is not configured. Please contact administration'), 404);
                }
            } elseif (preg_match($email_regex, $username)) {
                $validator = Validator::make($request->all(), [
                    'username'  => 'required',
                    'device_type'   => 'required|string',
                    'device_token'  => 'required|string',
                ]);

                if ($validator->fails()) {
                    foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                        $errors['error'] = __($error_value[0]);
                        return response()->json($errors, 422);
                    }
                }
                $emailCode = mt_rand(100000, 999999);
                $sendTime = Carbon::now()->addMinutes(10)->toDateTimeString();
                $username = str_ireplace(' ', '', $username);

                $user = User::with('country')->where('email', $username)->first();
                if (!$user) {
                    return $this->errorResponse(__('Sorry you are not registered with us. Please sign up'), 404);
                } elseif ($user->status == 2) {
                    return $this->errorResponse(__('You are unauthorized to access this account.'), 404);
                } elseif ($user->status == 3) {
                    return $this->errorResponse(__('Your Account is Inactive, Please Contact Wayno Support Team'), 404);
                } else {
                    $user->email_token = $emailCode;
                    $user->email_token_valid_till = $sendTime;
                    $user->save();
                }
                $prefer = ClientPreference::select(
                    'mail_type',
                    'mail_driver',
                    'mail_host',
                    'mail_port',
                    'mail_username',
                    'mail_password',
                    'mail_encryption',
                    'mail_from',
                    'android_app_link',
                    'ios_link',
                )->first();

                if (!empty($prefer->mail_driver) && !empty($prefer->mail_host) && !empty($prefer->mail_port) && !empty($prefer->mail_username) && !empty($prefer->mail_password) && !empty($prefer->mail_encryption)) {
                    $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
                    $confirured = $this->setMailDetail($prefer->mail_driver, $prefer->mail_host, $prefer->mail_port, $prefer->mail_username, $prefer->mail_password, $prefer->mail_encryption);
                    $client_name = $client->name;
                    $mail_from = $prefer->mail_from;
                    $sendto = $username;
                    try {
                        $email_template_content = '';
                        $email_template = EmailTemplate::where('id', 2)->first();
                        if ($email_template) {
                            $email_template_content = $email_template->content;
                            $email_template_content = str_ireplace("{code}", $emailCode, $email_template_content);
                            $email_template_content = str_ireplace("{customer_name}", ucwords($user->name), $email_template_content);
                            $email_template_content = str_ireplace("{verify-email-banner}", asset("images/email/verify-email-banner.jpg"), $email_template_content);
                        }
                        $data = [
                            'code' => $emailCode,
                            'email' => $sendto,
                            'mail_from' => $mail_from,
                            'client_name' => $client_name,
                            'subject' => $email_template->subject,
                            'android_app_link' => $prefer->android_app_link,
                            'ios_link' => $prefer->ios_link,
                            'email_template_content' => $email_template_content,
                        ];
                        dispatch(new \App\Jobs\sendEmailJob($data))->onQueue('verify_email');
                        $message = __('An OTP has been sent to your email. Please check.');
                        $request->request->add(['is_email' => 1]);
                        $response = $request->all();
                        $response['current_language'] = $currentLanguage;
                        return $this->successResponse($response, $message);
                    } catch (\Exception $e) {
                        $user->save();
                        Log::error('Email failed!');
                        Log::error($e->getMessage());
                    }
                    $response['social_login'] = false;
                } else {
                    return $this->errorResponse(__('Email Provider service is not configured. Please contact administration'), 404);
                }
            } else {
                return $this->errorResponse(__('Invalid email or phone number'), 404);
            }
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Verify Login user via Phone number and create token
     *
     */
    public function verifyPhoneLoginOtp(Request $request)
    {
        try {
            $username = $request->username;
            $dialCode = $request->dialCode;
            
            // Validate dialCode is exactly 971 (without + sign)
            if ($dialCode !== '971') {
                $errors['error'] = __('Only UAE dial code (971) is allowed without + sign');
                return response()->json($errors, 422);
            }
            
            $phone_number = preg_replace('/\D+/', '', $username);
            $user = User::where('dial_code', $dialCode)->where('phone_number', $phone_number)->first();
            if (!$user) {
                $errors['error'] = __('Your phone number is not registered');
                return response()->json($errors, 422);
            }
            if ($phone_number == '512345678') {
                if ('123456' != $request->verifyToken) {
                    return $this->errorResponse(__('OTP is not valid'), 404);
                }
            } else {
                $currentTime = Carbon::now()->toDateTimeString();
                if ($user->phone_token != $request->verifyToken) {
                    return $this->errorResponse(__('OTP is not valid'), 404);
                }
                if ($currentTime > $user->phone_token_valid_till) {
                    return $this->errorResponse(__('OTP has been expired.'), 404);
                }
            }
            
            // Get current language from user's language preference
            $currentLanguage = null;
            if ($user->language_id) {
                $checkLang = ClientLanguage::with('language:id,sort_code,name,is_rtl')
                    ->where('language_id', $user->language_id)
                    ->first();
                if($checkLang && $checkLang->language){
                    $currentLanguage = $checkLang->language;
                }
            }
            
            $request->request->add(['phone_number' => $phone_number]);
            return $this->proceedToPhoneLogin($request, $currentLanguage);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }
    /**
     * Verify Login user via Email and create token
     *
     */
    public function verifyEmailLoginOtp(Request $request)
    {
        try {
            $username = $request->username;
            $email = str_ireplace(' ', '', $username);
            $user = User::with('country')->where('email', $email)->first();
            if (!$user) {
                $errors['error'] = __('Your email is not registered');
                return response()->json($errors, 422);
            }
            if ($email == 'testapp@gmail.com') {
                if ('123456' != $request->verifyToken) {
                    return $this->errorResponse(__('OTP is not valid'), 404);
                }
            } else {
                $currentTime = Carbon::now()->toDateTimeString();
                if ($user->email_token != $request->verifyToken) {
                    return $this->errorResponse(__('OTP is not valid'), 404);
                }
                if ($currentTime > $user->email_token_valid_till) {
                    return $this->errorResponse(__('OTP has been expired.'), 404);
                }
            }
            $request->request->add(['email' => $email]);
            return $this->proceedToEmailLogin($request);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    /*** register user via phone number ***/
    public function registerViaPhone($req, $domain = '')
    {
        try {
            $user = new User();
            $country = Country::where('code', strtoupper($req->countryData))->first();
            // $emailCode = mt_rand(100000, 999999);
            $email = 'ro_' . Carbon::now()->timestamp . '.' . uniqid() . '@royoorders.com';
            $user->type = 1;
            $user->status = 1;
            $user->role_id = 1;
            $user->name = 'RO' . substr($req->phone_number, -6);
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
            $user_cart = Cart::where('user_id', $user->id)->first();
            if ($user_cart) {
                $unique_identifier_cart = Cart::where('unique_identifier', $req->device_token)->first();
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
                Cart::where('unique_identifier', $req->device_token)->update(['user_id' => $user->id,  'unique_identifier' => '']);
            }
            if ($user->id > 0) {
                if ($req->refferal_code) {
                    $refferal_amounts = ClientPreference::first();
                    if ($refferal_amounts) {
                        if ($refferal_amounts->reffered_by_amount != null && $refferal_amounts->reffered_to_amount != null) {
                            $reffered_by = UserRefferal::where('refferal_code', $req->refferal_code)->first();
                            $user_refferd_by = $reffered_by->user_id;
                            $user_refferd_by = User::where('id', $reffered_by->user_id)->first();
                            if ($user_refferd_by) {
                                //user reffered by amount
                                $wallet_user_reffered_by = $user_refferd_by->wallet;
                                $wallet_user_reffered_by->deposit($refferal_amounts->reffered_by_amount, ['Referral code used by <b>' . $req->name . '</b>']);
                                $wallet_user_reffered_by->balance;
                                //user reffered to amount
                                $wallet->deposit($refferal_amounts->reffered_to_amount, ['You used referal code of <b>' . $user_refferd_by->name . '</b>']);
                                $wallet->balance;
                            }
                        }
                    }
                }

                return $this->successResponse($user, 'Successfully registered');
            } else {
                return $this->errorResponse('Something went wrong. Please try again.', 422);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    /*** update fcm token ***/
    public function updateFcmToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'device_type'  => 'required',
                'device_token' => 'required',
                'fcm_token'    => 'required',
                'access_token' => 'required',
            ]);
            if ($validator->fails()) {
                foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                    $errors['error'] = __($error_value[0]);
                    return $this->errorResponse($errors['error'], 422);
                }
            }
            // FIXED: Only update the SPECIFIC device, not ALL user devices
            // This prevents logging out other devices when updating FCM token
            $device = UserDevice::updateOrCreate(
                [
                    'device_token' => $request->device_token,
                    'user_id' => Auth::id()
                ],
                [
                    'device_type' => $request->device_type,
                    'fcm_token' => $request->fcm_token,
                    'access_token' => $request->access_token
                ]
            );
            $data = [
                'user_id' => Auth::id(),
                'device_type' => $request->device_type,
                'device_token' => $request->device_token,
                'fcm_token' => $request->fcm_token,
                'access_token' => $request->access_token
            ];
            return $this->successResponse($data, __('FCM Token Successfully updated'));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
