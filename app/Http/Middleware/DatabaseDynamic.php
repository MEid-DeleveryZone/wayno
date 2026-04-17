<?php

namespace App\Http\Middleware;

use Config;
use Closure;
use Session;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client as TwilioC;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\{Client, ClientPreference,Country};

class DatabaseDynamic{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if(Auth::check()){
            
          $client = Client::first();
           if($client){
              $database_name = 'royo_'.$client->database_name;
              $database_name = 'royo_'.$client->database_name;
              $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST','127.0.0.1');
              $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT','3306');
              $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME','royoorders');
              $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD','');

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
              Config::set("client_id",1);
              Config::set("client_connected",true);
              Config::set("client_data",$client);
              DB::setDefaultConnection($database_name);
              DB::purge($database_name);

              $clientPreference = ClientPreference::first();

              Session::put('login_user_type', 'client');
              if(isset($clientPreference)){
                $agentTitle = empty($clientPreference->agent_name) ? 'Agent' : $clientPreference->agent_name;
                Session::put('agent_name', $agentTitle);
                Session::put('preferences', $clientPreference->toArray());

              }else{
                Session::put('agent_name', 'Agent');
                Session::put('preferences', '');
              }
              if($clientPreference){
                if(!empty($clientPreference->sms_provider_key_1) && !empty($clientPreference->sms_provider_key_2)){
                  $token = $clientPreference->sms_provider_key_1;
                  $sid = $clientPreference->sms_provider_key_2;
                  $twilio = new TwilioC($sid, $token);
                  try {
                    $account = $twilio->api->v2010->accounts($sid)->fetch();
                    Session::put('twilio_status', $account->status);
                  } catch (\Exception $e) {
                      Session::put('twilio_status', 'invalid_key');
                  }
                }else{
                  Session::put('twilio_status', 'null_key');
                }

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
          }
      }
        return $next($request);
    }
}
