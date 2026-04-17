<?php

namespace App\Http\Middleware;

use Cache;
use Config;
use Closure;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Models\{Client, ClientPreference, Language, ClientLanguage, Currency, ClientCurrency, Product,Country};

class CustomDomain{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
      $path = $request->path();
      $domain = $request->getHost();
      $domain = str_replace(array('http://', '.test.com/login'), '', $domain);
      $subDomain = explode('.', $domain);
      $existRedis = Redis::get($domain);
      if(!$existRedis){
        $client = Client::select('name', 'email', 'phone_number', 'is_deleted', 'is_blocked', 'logo', 'company_name', 'company_address', 'status', 'code', 'database_name', 'database_host', 'database_port', 'database_username', 'database_password', 'custom_domain', 'sub_domain')
                    ->where(function($q) use($domain, $subDomain){
                              $q->where('custom_domain', $domain)
                                ->orWhere('sub_domain', $subDomain[0]);
                    })->firstOrFail();
        Redis::set($domain, json_encode($client->toArray()), 'EX', 36000);
        $existRedis = Redis::get($domain);
      }
      $callback = '';
      $redisData = json_decode($existRedis);
      if($redisData){
          $database_name = 'royo_'.$redisData->database_name;
          $database_host = !empty($redisData->database_host) ? $redisData->database_host : env('DB_HOST', '127.0.0.1');
          $database_port = !empty($redisData->database_port) ? $redisData->database_port : env('DB_PORT', '3306');
          $database_username = !empty($redisData->database_username) ? $redisData->database_username : env('DB_USERNAME', 'royoorders');
          $database_password = !empty($redisData->database_password) ? $redisData->database_password : env('DB_PASSWORD', '');
          $default = [
              'driver' => env('DB_CONNECTION','mysql'),
              'host' => $database_host,
              'port' => $database_port,
              'database' => $database_name,
              'username' => $database_username,
              'password' => $database_password,
              'charset' => 'utf8mb4',
              'collation' => 'utf8mb4_unicode_ci',
              'prefix' => '',
              'prefix_indexes' => true,
              'strict' => false,
              'engine' => null
          ];
          Config::set("database.connections.$database_name", $default);
          Config::set("client_id", 1);
          Config::set("client_connected", true);
          Config::set("client_data", $redisData);
          DB::setDefaultConnection($database_name);
          DB::purge($database_name);
          if(!empty($redisData->custom_domain)){
            $domain = rtrim($redisData->custom_domain, "/");
            $domain = ltrim($domain, "https://");
            $callback = "https://".$domain.'/auth/facebook/callback';
          }else{
            $sub_domain = rtrim($redisData->sub_domain, "/");
            $sub_domain = ltrim($sub_domain, "https://");
            $callback = "https://".$sub_domain.".royoorders.com/auth/facebook/callback";
          }
          $clientPreference = ClientPreference::select('theme_admin', 'distance_unit', 'currency_id', 'date_format', 'time_format', 'fb_login', 'fb_client_id', 'fb_client_secret', 'fb_client_url', 'twitter_login', 'twitter_client_id', 'twitter_client_secret', 'twitter_client_url', 'google_login', 'google_client_id', 'google_client_secret', 'google_client_url', 'apple_login', 'apple_client_id', 'apple_client_secret', 'apple_client_url', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'map_provider', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'web_template_id', 'is_hyperlocal', 'need_delivery_service', 'need_dispacher_ride', 'delivery_service_key', 'dispatcher_key', 'primary_color', 'secondary_color', 'fcm_api_key', 'fcm_auth_domain', 'fcm_project_id', 'fcm_storage_bucket', 'fcm_messaging_sender_id', 'fcm_app_id', 'fcm_measurement_id', 'distance_unit_for_time', 'distance_to_time_multiplier')->where('client_code', $redisData->code)->first();
          if($clientPreference){
            Config::set('FACEBOOK_CLIENT_ID', $clientPreference->fb_client_id);
            Config::set('FACEBOOK_CLIENT_SECRET', $clientPreference->fb_client_secret);
            Config::set('FACEBOOK_CALLBACK_URL', $callback);
          }
          Session::put('client_config', $redisData);
          Session::put('login_user_type', 'client');

          // Set language
          $primeLang = ClientLanguage::select('language_id', 'is_primary')->where('is_primary', 1)->first();
          if (!Session::has('customerLanguage') || empty(Session::get('customerLanguage'))){
              if($primeLang){
                Session::put('customerLanguage', $primeLang->language_id);
              }
          }
          if(!Session::has('customerLanguage') || empty(Session::get('customerLanguage'))){
            $primeLang = Language::where('id', 1)->first();
            Session::put('customerLanguage', 1);
          }
          $lang_detail = Language::where('id', Session::get('customerLanguage'))->first();
          App::setLocale($lang_detail->sort_code);
          Session::put('applocale', $lang_detail->sort_code);

          // Set Currency
          $primeCurcy = ClientCurrency::join('currencies as cu', 'cu.id', 'client_currencies.currency_id')->where('client_currencies.is_primary', 1)->first();
          if (!Session::has('customerCurrency') || empty(Session::get('customerCurrency'))){
              if($primeCurcy){
                Session::put('customerCurrency', $primeCurcy->currency_id);

                Session::put('currencyMultiplier', $primeCurcy->doller_compare);
                if($primeCurcy->iso_code == "AED"){
                    Session::put('currencySymbol', $primeCurcy->iso_code);
                }else{
                    Session::put('currencySymbol', $primeCurcy->symbol);
                }
              }
          }
          if (!Session::has('customerCurrency') || empty(Session::get('customerCurrency'))){
            $primeCurcy = Currency::where('id', 147)->first();
            Session::put('customerCurrency', 147);
            if($primeCurcy->iso_code == "AED"){
                Session::put('currencySymbol', $primeCurcy->iso_code);
            }else{
                Session::put('currencySymbol', $primeCurcy->symbol);
            }

            Session::put('currencyMultiplier', 1);
          }
          $currency_detail = Currency::where('id', Session::get('customerCurrency'))->first();
          Session::put('iso_code', $currency_detail->iso_code);

          // Client preferences
          $preferData = array();
          if(isset($clientPreference)){
            $preferData = $clientPreference;
          }

          $cl = Client::first();
          $getAdminCurrentCountry = Country::where('id', '=', $cl->country_id)->get()->first();
          if(!empty($getAdminCurrentCountry)){
            $countryCode = $getAdminCurrentCountry->code;
            $phoneCode = $getAdminCurrentCountry->phonecode;
          }else{
            $countryCode = '';
            $phoneCode = '';
          }

          Session::put('default_country_code', $countryCode);
          Session::put('default_country_phonecode', $phoneCode);

          Session::put('preferences', $preferData);
      }else{
        return redirect()->route('error_404');
      }
      return $next($request);
    }
}
