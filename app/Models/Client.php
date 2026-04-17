<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class Client extends Authenticatable
{
    use Notifiable;
    protected $guard = 'client';
    protected $fillable = [
        'name', 'email', 'password', 'encpass', 'phone_number', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain','status', 'code', 'country_id', 'timezone', 'is_deleted', 'is_blocked','sub_domain'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime'];

    /**
     * Get Clientpreference
    */
    public function getPreference()
    {
      return $this->hasOne('App\Models\ClientPreference','client_code','code');
    }

    /**
     * Get Clientpreference
    */
    public function preferences()
    {
      return $this->hasOne('App\Models\ClientPreference', 'client_code', 'code')->select('business_type','theme_admin', 'client_code', 'distance_unit', 'currency_id', 'dinein_check', 'takeaway_check', 'delivery_check', 'date_format', 'time_format', 'fb_login', 'twitter_login', 'google_login', 'apple_login', 'map_provider', 'app_template_id', 'is_hyperlocal', 'verify_email', 'verify_phone', 'primary_color', 'secondary_color', 'map_key', 'pharmacy_check','celebrity_check','enquire_mode','subscription_mode','site_top_header_color', 'tip_before_order', 'tip_after_order', 'off_scheduling_at_cart','max_return_days', 'max_cod_amount', 'max_wallet_refund');
    }

    public function defaultAddress()
    {
      return $this->hasOne('App\Models\ClientPreference', 'client_code', 'code')->select('client_code','Default_location_name as address', 'Default_latitude as latitude', 'Default_longitude as longitude');
    }

    public function getEncpassAttribute($value)
    {
      $value1 = $value;
      if(!empty($value)){
        $value1 = Crypt::decryptString($value);
      }
      return $value1;
    }

    public function setEncpassAttribute($value)
    {
        $this->attributes['encpass'] = Crypt::encryptString($value);
    }

    /**
     * Get Allocation Rules
    */
    public function getAllocation()
    {
      return $this->hasOne('App\Model\AllocationRule','client_id','code');
    }

    public function rules($id = ''){
        $rules = array(
            'name' => 'required|string|max:50',
            'phone_number' => 'required',
            //'database_path' => 'required',
            //'database_username' => 'required|max:50',
            //'database_password' => 'required|max:50',
            'company_name' => 'required',
            'company_address' => 'required',
            'sub_domain' => 'required',
        );

        if(empty($id)){
            $rules['email'] = 'required|email|max:60|unique:clients';
            $rules['encpass'] = 'required|string|max:60|min:6';
            $rules['database_name'] = 'required|max:60|unique:clients';
        }

        /*if(!empty($id)){
            $rule['email'] = 'email|max:60|unique:clients,email,'.$id;
            $rule['database_name'] = 'max:60|unique:clients,database_name,'.$id;
        }*/
        return $rules;
    }

    public function getLogoAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');
      $values['original'] = \Storage::disk('s3')->url($img);
      $values['logo_db_value'] = $value;
      
      return $values;
    }


    public function getCodeAttribute($value)
    { 
      if(!empty($this->attributes['id'])){
        $value = str_replace($this->attributes['id']."_",'',$value);
      }
      return $value;
    }

    public function country()
    {
      return $this->belongsTo('App\Models\Country','country_id','id');
    }    

}