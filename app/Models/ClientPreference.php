<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPreference extends Model
{
    protected $fillable = ['client_code', 'theme_admin', 'distance_unit', 'currency_id', 'language_id', 'date_format', 'time_format', 'fb_client_id', 'fb_client_secret', 'fb_client_url', 'twitter_client_id', 'twitter_client_secret', 'twitter_client_url', 'google_client_id', 'google_client_secret', 'google_client_url', 'apple_client_id', 'apple_client_secret', 'apple_client_url', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'map_provider', 'map_key', 'map_secret', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'verify_email', 'verify_phone','pharmacy_check', 'web_template_id', 'app_template_id', 'is_hyperlocal', 'need_delivery_service', 'dispatcher_key_1', 'dispatcher_key_2','need_dispacher_home_other_service','dispacher_home_other_service_key','dispacher_home_other_service_key_url','dispacher_home_other_service_key_code','last_mile_team','tip_before_order','tip_after_order','off_scheduling_at_cart', 'distance_unit_for_time', 'distance_to_time_multiplier','business_type', 'fcm_server_key', 'fcm_api_key', 'fcm_auth_domain', 'fcm_project_id', 'fcm_storage_bucket', 'fcm_messaging_sender_id', 'fcm_app_id', 'fcm_measurement_id', 'android_maintenance_mode', 'ios_maintenance_mode', 'android_app_force_update_status', 'ios_app_force_update_status'];

    public function filling(){
    	$filling = ['theme_admin', 'distance_unit', 'currency_id', 'date_format', 'time_format', 'fb_client_id', 'fb_client_secret', 'fb_client_url', 'twitter_client_id', 'twitter_client_secret', 'twitter_client_url', 'google_client_id', 'google_client_secret', 'google_client_url', 'apple_client_id', 'apple_client_secret', 'apple_client_url', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'map_provider', 'map_key', 'map_secret', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'verify_email', 'verify_phone', 'web_template_id', 'app_template_id', 'pharmacy_check'];

    	return $filling;
    }

    public function language()
    {
      return $this->hasMany('App\Models\ClientLanguage','client_code','client_code')->select( 'client_code', 'language_id', 'is_primary')->where('is_active', 1);
    }

    public function primarylang()
    {
      return $this->hasOne('App\Models\ClientLanguage','client_code','client_code')->select( 'client_code', 'language_id')->where('is_primary', 1);
    }

    public function currency()
    {
      return $this->hasMany('App\Models\ClientCurrency','client_code','client_code')->select( 'client_code', 'currency_id', 'doller_compare')->where('is_primary', 0);
    }


    public function primary()
    {
      return $this->hasone('App\Models\ClientCurrency','client_code','client_code')->select( 'client_code', 'currency_id')->where('is_primary', 1);
    }


    public function domain()
    {
      return $this->belongsTo('App\Models\Client','client_code','code')->select('id', 'code', 'custom_domain');
    }
 
    public function getFaviconAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');

      //$values['small'] = url('showImage/small/' . $img);
      return $values;
    }

    public function client_detail()
    {
      return $this->belongsTo('App\Models\Client','client_code','code');
    }

}