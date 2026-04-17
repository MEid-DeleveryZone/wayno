<?php

namespace App\Models;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Interfaces\WalletFloat;
use App\Notifications\PasswordReset;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Wallet, WalletFloat
{
    use Notifiable;
    use HasWallet;
    use HasWalletFloat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'description', 'phone_number', 'dial_code','image', 'email_verified_at', 'is_verified_phone', 'type', 'status', 'device_type', 'device_token', 'country_id', 'role_id', 'auth_token', 'remember_token', 'timezone', 'language_id', 'currency_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function country(){
       return $this->belongsTo('App\Models\Country')->select('id', 'code', 'name','phonecode'); 
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    public function address(){
       return $this->hasMany('App\Models\UserAddress'); 
    }

    public function role(){
       return $this->belongsTo('App\Models\Role')->select('id', 'role'); 
    }
   
    public function device(){
       return $this->hasMany('App\Models\UserDevice'); 
    }
    /*
    bucketname:- royoorders2.0-assets

        IAM user:-royoorders2.0S3Access
        Access key ID:- AKIAUDRAUVRKEJPQVO4C
        Secret access key :- 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
             
    */

    public function getImageAttribute($value)
    {
      $values = array();
      $img = 'default/default_avatar.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');
      $values['original'] = $value;

      return $values;
    }

    public function rules($id = ''){
        $rules = array(
            'name'          => 'required|string|min:3|max:50',
            'email'         => 'required|email|max:50||unique:users',
            'password'      => 'required|string|min:6|max:50',
            'phone_number'  => 'required|string|min:8|max:15|unique:users',
        );

        /*if(!empty($id)){
            $rule['email'] = 'email|max:60|unique:clients,email,'.$id;
            $rule['database_name'] = 'max:60|unique:clients,database_name,'.$id;
        }*/
        return $rules;
    }

    public function orders(){
       return $this->hasMany('App\Models\Order', 'user_id', 'id')->select('id', 'user_id'); 
    }

    public function activeOrders(){
       return $this->hasMany('App\Models\Order', 'user_id', 'id')->select('id', 'user_id')
              ->where('is_deleted', '!=', 1); 
    }

    /**
     * Get All permisions
    */
    public function getAllPermissions()
    {
      return $this->hasMany('App\Models\UserPermissions','user_id','id');
    }


    public function getCodeAttribute($value)
    { 
     $value = Client::first();
     return $value->code;
    }

    public function currentlyWorkingOrders(){
        return $this->hasMany('App\Models\Order', 'user_id', 'id')->select('id', 'user_id')
               ->where('is_deleted', '!=', 1)->whereHas('orderStatusVendor', function($query){
                   $query->whereIn('order_status_option_id',[2,4,5]);
               }); 
    }
}
