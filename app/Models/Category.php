<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
class Category extends Model
{
  use SoftDeletes;
  
    protected $fillable = ['slug','icon', 'image', 'order_details_image', 'is_visible', 'status', 'position', 'is_core', 'can_add_products', 'parent_id', 'vendor_id', 'client_code', 'display_mode', 'type_id','warning_page_id', 'template_type_id', 'warning_page_design', 'is_pickup_enabled', 'is_dropoff_enabled', 'is_prohibited_item_enabled', 'is_vehicle_number_required', 'is_show_products_in_popup'];
    public $timestamps = true;

    public function translation(){
      return $this->hasMany('App\Models\Category_translation')->join('client_languages as cl', 'cl.language_id', 'category_translations.language_id')->join('languages', 'category_translations.language_id', 'languages.id')->select('category_translations.*', 'languages.id as langId', 'languages.name as langName', 'cl.is_primary')->where('cl.is_active', 1)->orderBy('cl.is_primary', 'desc'); 
    }
    
    public function translation_one(){
     
        $primary = ClientLanguage::orderBy('is_primary','desc')->first();
        if(isset($primary) && !empty($primary))
        {
          $langset = $primary->language_id ;
        }else{
          $langset = 1;
        }
        
        return $this->hasOne('App\Models\Category_translation')->select('category_id', 'name')->where('language_id', $langset); 
      
     
       
    }

    public function english(){
      $primary = ClientLanguage::orderBy('is_primary','desc')->first();
      if(isset($primary) && !empty($primary))
      {
        $langset = $primary->language_id ;
      }else{
        $langset = 1;
      }
       return $this->hasOne('App\Models\Category_translation')->select('category_id', 'name')->where('language_id', $langset); 
    }
    
    public function primary(){

      $langData = $this->hasOne('App\Models\Category_translation')->join('client_languages as cl', 'cl.language_id', 'category_translations.language_id')->select('category_translations.category_id', 'category_translations.name', 'category_translations.language_id')->where('cl.is_primary', 1);

      if(!$langData){
        $langData = $this->hasOne('App\Models\Category_translation')->join('client_languages as cl', 'cl.language_id', 'category_translations.language_id')->select('category_translations.category_id', 'category_translations.name', 'category_translations.language_id')->limit(1);
      }
      return $langData;
    }

    public function tags()
    {
        return $this->hasMany(CategoryTag::class)->select('category_id', 'tag');
    }

    public function brands()
    {
        return $this->hasMany(BrandCategory::class)->join('brands', 'brands.id', 'brand_categories.brand_id')
                ->select('brand_categories.category_id', 'brand_categories.brand_id', 'brands.id', 'brands.image');
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->select('id', 'slug', 'parent_id', 'icon','image','type_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
    public function type(){
      return $this->belongsTo('App\Models\Type')->select('id', 'title'); 
    }

    public function categoryTag()
    {
        return $this->hasOne(CategoryTag::class)->select('category_id', 'tag');
    }

    public function getImageAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');
      return $values;
    }

    public function getIconAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');
      return $values;
    }

    public function getOrderDetailsImageAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      
      // Check if the image is a GIF file
      $isGif = false;
      if (!empty($value)) {
        $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
        $isGif = ($extension === 'gif');
      }
      
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');
      
      // For GIF files, provide a direct URL that bypasses the image proxy
      if ($isGif) {
        $values['direct_url'] = \Storage::disk('s3')->url($img);
      }
      
      return $values;
    }

    public function parent(){
      return $this->belongsTo('App\Models\Category','parent_id','id'); 
   }

  public function allParentsAccount()
  {
    return $this->parent()->select('id', 'slug', 'parent_id')->with('allParentsAccount');
  }



  public function translationSet(){
    return $this->hasMany('App\Models\Category_translation'); 
  }


  public function translationSetUnique(){
    return $this->hasMany('App\Models\Category_translation')->join('client_languages as cl', 'cl.language_id', 'category_translations.language_id')->join('languages', 'category_translations.language_id', 'languages.id')->select('category_translations.*', 'languages.id as langId', 'languages.name as langName', 'cl.is_primary')->where('cl.is_active', 1)->groupBy('category_translations.language_id')->orderBy('cl.is_primary', 'desc'); 
  }

}
