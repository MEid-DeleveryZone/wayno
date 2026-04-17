<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
//use Laravel\Scout\Searchable;

class Vendor extends Model
{
  //use Searchable;
  protected $fillable = ['name', 'slug', 'desc', 'logo', 'banner', 'address', 'email', 'website', 'phone_no', 'customer_care_number', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'commission_percent', 'commission_fixed_per_order', 'commission_monthly', 'dine_in', 'takeaway', 'delivery', 'status', 'add_category', 'setting', 'show_slot', 'vendor_templete_id', 'auto_accept_order', 'payment_options', 'is_cancel'];

  public function serviceArea()
  {
    return $this->hasMany('App\Models\ServiceArea')->select('vendor_id', 'geo_array', 'name');
  }

  public function products()
  {
    return $this->hasMany('App\Models\Product', 'vendor_id', 'id');
  }

  public function slot()
  {
    $client = Client::first();
    $mytime = Carbon::now()->setTimezone($client->timezone);
    $current_time = $mytime->toTimeString();
    return $this->hasMany('App\Models\VendorSlot', 'vendor_id', 'id')->has('day')->where('start_time', '<', $current_time)->where('end_time', '>', $current_time);
  }

  public function slotDate()
  {
    $client = Client::first();
    $mytime = Carbon::now()->setTimezone($client->timezone);
    $current_date = $mytime->toDateString();
    $current_time = $mytime->toTimeString();
    return $this->hasMany('App\Models\VendorSlotDate', 'vendor_id', 'id')->where('specific_date', '=', $current_date)->where('start_time', '<', $current_time)->where('end_time', '>', $current_time);
  }

  public function avgRating()
  {
    return $this->hasMany('App\Models\Product', 'vendor_id', 'id')->avg('averageRating');
  }

  public function getLogoAttribute($value)
  {
    $values = array();
    $img = 'default/default_image.png';
    if (!empty($value)) {
      $img = $value;
    }
    $values['proxy_url'] = \Config::get('app.IMG_URL1');
    if (substr($img, 0, 7) == "http://" || substr($img, 0, 8) == "https://") {
      $values['image_path'] = \Config::get('app.IMG_URL2') . '/' . $img;
    } else {
      $values['image_path'] = \Config::get('app.IMG_URL2') . '/' . \Storage::disk('s3')->url($img);
    }
    $values['image_fit'] = \Config::get('app.FIT_URl');
    return $values;
  }

  public function getBannerAttribute($value)
  {
    $values = array();
    $img = 'default/default_image.png';
    if (!empty($value)) {
      $img = $value;
    }
    $values['proxy_url'] = \Config::get('app.IMG_URL1');
    if (substr($img, 0, 7) == "http://" || substr($img, 0, 8) == "https://") {
      $values['image_path'] = \Config::get('app.IMG_URL2') . '/' . $img;
    } else {
      $values['image_path'] = \Config::get('app.IMG_URL2') . '/' . \Storage::disk('s3')->url($img);
    }
    $values['image_fit'] = \Config::get('app.FIT_URl');
    return $values;
  }
  public static function getNameById($vendor_id)
  {
    $result = Vendor::where('id', $vendor_id)->first();
    return $result->name;
  }

  public function orders()
  {
    return $this->hasMany('App\Models\OrderVendor', 'vendor_id', 'id');
  }

  public function activeOrders()
  {
    return $this->hasMany('App\Models\OrderVendor', 'vendor_id', 'id')->select('id', 'vendor_id')
      ->where('status', '!=', 3);
  }

  public function permissionToUser()
  {
    return $this->hasMany('App\Models\UserVendor');
  }


  public function product()
  {
    return $this->hasMany('App\Models\Product', 'vendor_id', 'id');
  }

  public function currentlyWorkingOrders()
  {
    return $this->hasMany('App\Models\OrderVendor', 'vendor_id', 'id')->select('id', 'vendor_id')
      ->whereIn('order_status_option_id', [2, 4, 5]);
  }

  public function vendorCategories()
  {
    return $this->hasMany('App\Models\VendorCategory');
  }
}