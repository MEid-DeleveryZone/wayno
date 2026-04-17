<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Password;
use JWT\Token;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{User, Product, Cart, ClientCurrency, Brand, CartAddon, UserDevice, ClientPreference, CartProduct};

class SocialController extends BaseController{
	/**
     * Social Keys
     */
    public function getKeys(Request $request){
        if($request->type != 'facebook' && $request->type != 'twitter' && $request->type != 'google' && $request->type != 'apple'){
            return response()->json(['error' => 'Invalid request'], 404);
        }
        $prefer = ClientPreference::where('id', '>', 0);
        if($request->type == 'facebook'){
            $prefer = $prefer->select('fb_client_id', 'fb_client_secret');
        } elseif($request->type == 'twitter'){
            $prefer = $prefer->select('twitter_client_id', 'twitter_client_secret');
        } elseif($request->type == 'google'){
            $prefer = $prefer->select('google_client_id', 'google_client_secret');
        } elseif($request->type == 'apple'){
            $prefer = $prefer->select('apple_client_id', 'apple_client_secret');
        }
        $prefer = $prefer->first();
        return response()->json(['data' => $prefer]);
    }

    public function login(Request $request, $driver = ''){
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|min:3|max:50',
            'auth_id'       => 'required|string',
            'device_type'   => 'required|string',
            'device_token'  => 'required|string'
        ]);
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = __($error_value[0]);
                return response()->json($errors, 422);
            }
        }
        $email = ($request->has('email') && !empty($request->email)) ? $request->email : 'xyz';
        $customer = User::where('id', '>', 0)->where('email', $email)->first();
        if(!$customer){
            $customer = User::where('id', '>', 0);
            if($driver == 'facebook'){
                $customer = $customer->where('facebook_auth_id', $request->auth_id);
            } elseif ($driver == 'twitter'){
                $customer = $customer->where('twitter_auth_id', $request->auth_id);
            } elseif ($driver == 'google'){
                $customer = $customer->where('google_auth_id', $request->auth_id);
            }
            $customer = $customer->first();
            if(!$customer){
                $customer = new User();
                $customer->name = $request->name;
                $eml = $request->auth_id.'@'.$driver.'-xyz.com';
                $customer->email = ($request->has('email') && !empty($request->email)) ? $request->email : $eml;
                $customer->password = Hash::make($request->auth_id);
                $customer->type = 1;
                $customer->role_id = 1;
            }
        }
        if($driver == 'facebook'){
            $customer->facebook_auth_id = $request->auth_id;
        } elseif ($driver == 'twitter'){
            $customer->twitter_auth_id = $request->auth_id;
        } elseif ($driver == 'google'){
            $customer->google_auth_id = $request->auth_id;
        } elseif ($driver == 'apple'){
            $customer->apple_auth_id = $request->auth_id;
        }
        if($request->has('phone_number')){
            $customer->phone_number = $request->phone_number;
        }
        $customer->status = 1;
        $customer->is_email_verified = 1;
        // $customer->is_phone_verified = 0;
        $customer->save();
        $user_cart = Cart::where('user_id', $customer->id)->first();
        if($user_cart){
            $unique_identifier_cart = Cart::where('unique_identifier', $request->device_token)->first();
            if($unique_identifier_cart){
                $unique_identifier_cart_products = CartProduct::where('cart_id', $unique_identifier_cart->id)->get();
                foreach ($unique_identifier_cart_products as $unique_identifier_cart_product) {
                    $user_cart_product_detail = CartProduct::where('cart_id', $user_cart->id)->where('product_id', $unique_identifier_cart_product->product_id)->first();
                    if($user_cart_product_detail){
                        $user_cart_product_detail->quantity = ($unique_identifier_cart_product->quantity + $user_cart_product_detail->quantity);
                        $user_cart_product_detail->save();
                        $unique_identifier_cart_product->delete();
                    }else{
                      $unique_identifier_cart_product->cart_id = $user_cart->id;
                      $unique_identifier_cart_product->save();
                    }
                }
                $unique_identifier_cart->delete();
            }
        }else{
            Cart::where('unique_identifier', $request->device_token)->update(['user_id' => $customer->id,  'unique_identifier' => '']);
        }
        $token1 = new Token;
        $token = $token1->make([
            'issuedAt' => time(),
            'algorithm' => 'HS256',
            'key' => 'royoorders-jwt',
            'issuer' => 'royoorders.com',
            'expiry' => strtotime('+1 year'), // 1 year token validity - user must manually logout
        ])->get();
        $token1->setClaim('user_id', $customer->id);
        $customer->auth_token = $token;
        $customer->save();
        if($customer->id > 0){
            $checkSystemUser = $this->checkCookies($customer->id);

            // FIXED: Use composite key (device_token + user_id) to prevent session overwriting
            $user_device = UserDevice::updateOrCreate(
                [
                    'device_token' => $request->device_token,
                    'user_id' => $customer->id
                ],
                [
                    'device_type' => $request->device_type,
                    'fcm_token' => $request->fcm_token ?? $request->device_token,
                    'access_token' => $token
                ]
            );
            
            $response['status'] = 'Success';
            $response['auth_token'] =  $token;
            $response['name'] = $customer->name;
            $response['email'] = $customer->email;
            $response['is_admin'] = $customer->is_admin;
            $response['phone_number'] = $customer->phone_number;
            $verified['is_email_verified'] = 1;
            $verified['is_phone_verified'] = $customer->is_phone_verified;
            $client_preference = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
            $preferData['map_key'] = $client_preference->map_key;
            $preferData['theme_admin'] = $client_preference->theme_admin;
            $preferData['date_format'] = $client_preference->date_format;
            $preferData['time_format'] = $client_preference->time_format;
            $preferData['map_provider'] = $client_preference->map_provider;
            $preferData['sms_provider'] = $client_preference->sms_provider;
            $preferData['verify_email'] = $client_preference->verify_email;
            $preferData['verify_phone'] = $client_preference->verify_phone;
            $preferData['distance_unit'] = $client_preference->distance_unit;
            $preferData['app_template_id'] = $client_preference->app_template_id;
            $preferData['web_template_id'] = $client_preference->web_template_id;
            $response['client_preference'] = $preferData;
            $response['verify_details'] = $verified;
            $response['social_login'] = true;
            return response()->json(['data' => $response]);
        }else{
            return response()->json(['error' => 'Something went wrong. Please try again.']);
        }
    }
}