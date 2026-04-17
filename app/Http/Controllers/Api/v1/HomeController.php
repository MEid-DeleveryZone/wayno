<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use App;
use Config;
use Log;
use Validation;
use Carbon\Carbon;
use ConvertCurrency;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{User, MobileBanner, Category, Brand, Client, ClientPreference, Cms, Order, Banner, Vendor, VendorCategory, Category_translation, ClientLanguage, PaymentOption, Product, Country, Currency, ServiceArea, ClientCurrency, ProductCategory, BrandTranslation, Celebrity, UserVendor, AppStyling, Nomenclature, AppDynamicTutorial, EmailTemplate};
use Exception;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends BaseController
{
    use ApiResponser;

    private $curLang = 0;
    private $field_status = 2;

    /** Return header data, client profile and configure data */
    public function headerContent(Request $request)
    {
        try {
            $homeData = array();
            $langId = 1; // Default language ID
            $currentLanguage = null;
            
            if ($request->hasHeader('language')) {
                $langValue = $request->header('language');
                
                // Check if the value is numeric (old way: language_id) or string (new way: language code like 'en', 'ae')
                if(is_numeric($langValue)){
                    // Backward compatibility: support numeric language_id
                    $checkLang = ClientLanguage::with('language:id,sort_code,name,is_rtl')
                        ->where('language_id', $langValue)
                        ->first();
                    if($checkLang){
                        $langId = $checkLang->language_id;
                        $currentLanguage = $checkLang->language;
                    }
                } else {
                    // New way: support language codes like 'en', 'ae', 'ar'
                    $checkLang = ClientLanguage::whereHas('language', function($q) use ($langValue) {
                        $q->where('sort_code', $langValue);
                    })->with('language:id,sort_code,name,is_rtl')->first();
                    if($checkLang){
                        $langId = $checkLang->language_id;
                        $currentLanguage = $checkLang->language;
                    }
                }
            }
            
            // If no language found, get primary language
            if(!$currentLanguage) {
                $primaryLang = ClientLanguage::with('language:id,sort_code,name,is_rtl')
                    ->where('is_primary', 1)
                    ->first();
                if($primaryLang && $primaryLang->language) {
                    $currentLanguage = $primaryLang->language;
                }
            }
            $homeData['profile'] = Client::with(['preferences', 'country:id,name,code,phonecode', 'defaultAddress'])->select('country_id', 'company_name', 'code', 'sub_domain', 'logo', 'company_address', 'phone_number', 'email', 'contact_email', 'contact_phone_number')->first();
            $app_styling_detail = AppStyling::getSelectedData();
            foreach ($app_styling_detail as $app_styling) {
                $key = $app_styling['key'];
                $homeData['profile']->preferences->$key = __($app_styling['value']);
            }
            $takeaway_nomenclature = $this->getNomenclatureName('Takeaway', $langId, false);
            $search_nomenclature = $this->getNomenclatureName('Search', $langId, false);
            $vendors_nomenclature = $this->getNomenclatureName('Vendors', $langId, false);
            $homeData['profile']->preferences->takeaway_nomenclature = $takeaway_nomenclature;
            $homeData['profile']->preferences->search_nomenclature = $search_nomenclature;
            $homeData['profile']->preferences->vendors_nomenclature = $vendors_nomenclature;
            $homeData['languages'] = ClientLanguage::with('language')->select('language_id', 'is_primary')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
            $banners = Banner::select("id", "name", "description", "image", "image_mobile", "link", 'redirect_category_id', 'redirect_vendor_id')
                ->where('status', 1)->where('validity_on', 1)
                ->where(function ($q) {
                    $q->whereNull('start_date_time')->orWhere(function ($q2) {
                        $q2->whereDate('start_date_time', '<=', Carbon::now())
                            ->whereDate('end_date_time', '>=', Carbon::now());
                    });
                })->orderBy('sorting', 'asc')->get();
            if ($banners) {
                foreach ($banners as $key => $value) {
                    $bannerLink = '';
                    $is_show_category = null;
                    $vendor_name = null;
                    if (!empty($value->link) && $value->link == 'category') {
                        $bannerLink = $value->redirect_category_id;
                        if ($bannerLink) {
                            $categoryData = Category::where('status', '!=', $this->field_status)->where('id', $value->redirect_category_id)->with('translation_one')->first();
                            $value->redirect_name = (($categoryData) && ($categoryData->translation_one)) ? $categoryData->translation_one->name : '';
                        }
                    }
                    if (!empty($value->link) && $value->link == 'vendor') {
                        $bannerLink = $value->redirect_vendor_id;
                        if ($bannerLink) {
                            $vendorData = Vendor::select('id', 'slug', 'name', 'banner', 'show_slot', 'order_pre_time', 'order_min_amount', 'vendor_templete_id', 'latitude', 'longitude')->where('status', 1)->where('id', $value->redirect_vendor_id)->first();
                            if ($vendorData) {
                                $vendorData->is_show_category = ($vendorData->vendor_templete_id == 2 || $vendorData->vendor_templete_id == 4) ? 1 : 0;
                            }
                            $is_show_category = (($vendorData) && ($vendorData->vendor_templete_id == 1)) ? 0 : 1;
                            $value->is_show_category = $is_show_category;
                            $value->redirect_name = $vendorData->name ?? '';
                            $value->vendor = $vendorData;
                        }
                    }
                    $value->redirect_to = ucwords($value->link);
                    $value->redirect_id = $bannerLink;
                    unset($value->redirect_category_id);
                    unset($value->redirect_vendor_id);
                }
            }
            $mobile_banners = MobileBanner::select("id", "name", "description", "image", "link", 'redirect_category_id', 'redirect_vendor_id', 'language_id')
                ->where('status', 1)->where('validity_on', 1)
                ->where(function($q) use ($langId) {
                    $q->where('language_id', $langId)
                      ->orWhereNull('language_id');
                })
                ->with(['category:id,redirect_to_screen,type_id', 'category.type', 'vendor'])
                ->where(function ($q) {
                    $q->whereNull('start_date_time')->orWhere(function ($q2) {
                        $q2->whereDate('start_date_time', '<=', Carbon::now())
                            ->whereDate('end_date_time', '>=', Carbon::now());
                    });
                })->orderBy('sorting', 'asc')->get();
            if ($mobile_banners) {
                foreach ($mobile_banners as $key => $value) {
                    $bannerLink = '';
                    $is_show_category = null;
                    $vendor_name = null;
                    if (!empty($value->link) && $value->link == 'category') {
                        $bannerLink = $value->redirect_category_id;
                        if ($bannerLink) {
                            $categoryData = Category::where('status', '!=', $this->field_status)->where('id', $value->redirect_category_id)->with('translation_one')->first();
                            $value->redirect_name = (($categoryData) && ($categoryData->translation_one)) ? $categoryData->translation_one->name : '';
                        }
                    }
                    if (!empty($value->link) && $value->link == 'vendor') {
                        $bannerLink = $value->redirect_vendor_id;
                        if ($bannerLink) {
                            $vendorData = Vendor::select('id', 'slug', 'name', 'banner', 'show_slot', 'order_pre_time', 'order_min_amount', 'vendor_templete_id', 'latitude', 'longitude')->where('status', 1)->where('id', $value->redirect_vendor_id)->first();
                            if ($vendorData) {
                                $vendorData->is_show_category = ($vendorData->vendor_templete_id == 2 || $vendorData->vendor_templete_id == 4) ? 1 : 0;
                            }
                            $is_show_category = (($vendorData) && ($vendorData->vendor_templete_id == 1)) ? 0 : 1;
                            $value->is_show_category = $is_show_category;
                            $value->redirect_name = $vendorData->name ?? '';
                            $value->vendor = $vendorData;
                        }
                    }
                    $value->redirect_to = ucwords($value->link);
                    $value->redirect_id = $bannerLink;
                    unset($value->redirect_category_id);
                    unset($value->redirect_vendor_id);
                    unset($value->language_id);
                }
            }
            $currency  = ClientCurrency::with('currency')->select('currency_id', 'is_primary', 'doller_compare')->orderBy('is_primary', 'desc')->get();
            foreach ($currency  as $value) {
                if (isset($value->currency) && $value->currency->iso_code == "AED") {
                    $value->currency->symbol = $value->currency->iso_code;
                }
            }
            $homeData['banners'] = $banners;
            $homeData['mobile_banners'] = $mobile_banners;
            $homeData['currencies'] = $currency;
            $homeData['dynamic_tutorial'] = AppDynamicTutorial::orderBy('sort')->get();
            $stripe_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
            if ($stripe_creds) {
                $creds_arr = json_decode($stripe_creds->credentials);
            }
            $homeData['profile']->preferences->stripe_publishable_key = (isset($creds_arr->publishable_key) && (!empty($creds_arr->publishable_key))) ? $creds_arr->publishable_key : '';
            $homeData['current_language'] = $currentLanguage;
            $message = __('Data Listed Successfully');
            return $this->successResponse($homeData ,$message);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /** return dashboard content like categories, vendors, brands, products     */
    public function homepage(Request $request)
    {
        try {
            $vends = [];
            $vends = [];
            $homeData = [];
            $user = Auth::user();
            $langId = $user->language;
            $currency_id = $user->currency;
            $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
            $preferences = ClientPreference::select('distance_to_time_multiplier', 'distance_unit_for_time', 'is_hyperlocal', 'Default_location_name', 'Default_latitude', 'Default_longitude')->first();
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $user_geo[] = $latitude;
            $user_geo[] = $longitude;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $type = 'delivery';
            if ($request->has('type')) {
                if (empty($request->type)) {
                    $vendorData = Vendor::select('id', 'slug', 'name', 'desc', 'banner', 'order_pre_time', 'order_min_amount', 'vendor_templete_id', 'show_slot', 'latitude', 'longitude')->withAvg('product', 'averageRating');
                } else {
                    $vendorData = Vendor::select('id', 'slug', 'name', 'desc', 'banner', 'order_pre_time', 'order_min_amount', 'vendor_templete_id', 'show_slot', 'latitude', 'longitude')->withAvg('product', 'averageRating')->where($request->type, 1);
                    $type = $request->type;
                }
            } else {
                $vendorData = Vendor::select('id', 'slug', 'name', 'desc', 'banner', 'order_pre_time', 'order_min_amount', 'vendor_templete_id', 'show_slot', 'latitude', 'longitude')->withAvg('product', 'averageRating');
            }
            if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                if ((empty($latitude)) && (empty($longitude))) {
                    // return $this->successResponse(['reqData' => $request->all(), 'vendors' => []]); // Show nothing if location is empty
                    $address = $preferences->Default_location_name;
                    $latitude = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_latitude) : 0;
                    $longitude = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_longitude) : 0;
                    $request->request->add(['latitude' => $latitude, 'longitude' => $longitude, 'address' => $address]);
                }
                $vendorData = $vendorData->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                    $query->select('vendor_id')
                        ->whereRaw("ST_Contains(polygon, ST_GeomFromText('POINT(" . $latitude . " " . $longitude . ")'))");
                });
            }
            $vendorData = $vendorData->whereHas('vendorCategories.category', function (Builder $q) {
                $q->where('slug', '!=', 'Delivery');
            });
            $vendorData = $vendorData->with('slot', 'slotDate')->where('status', 1)->get();

            if (!count($vendorData)) {
                return $this->successResponse(['reqData' => $request->all(), 'vendors' => []]); // Show nothing if no vendor available for the location
            }

            foreach ($vendorData as $vendor) {
                unset($vendor->products);

                $vendor->is_vendor_closed = 0;
                if ($vendor->show_slot == 0) {
                    if (($vendor->slotDate->isEmpty()) && ($vendor->slot->isEmpty())) {
                        $vendor->is_vendor_closed = 1;
                    } else {
                        $vendor->is_vendor_closed = 0;
                        if ($vendor->slotDate->isNotEmpty()) {
                            $vendor->opening_time = Carbon::parse($vendor->slotDate->first()->start_time)->format('g:i A');
                            $vendor->closing_time = Carbon::parse($vendor->slotDate->first()->end_time)->format('g:i A');
                        } elseif ($vendor->slot->isNotEmpty()) {
                            $vendor->opening_time = Carbon::parse($vendor->slot->first()->start_time)->format('g:i A');
                            $vendor->closing_time = Carbon::parse($vendor->slot->first()->end_time)->format('g:i A');
                        }
                    }
                }

                $vendor->is_show_category = ($vendor->vendor_templete_id == 2 || $vendor->vendor_templete_id == 4) ? 1 : 0;

                $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $vendor->id)->where('status', 1)->get();
                $categoriesList = '';
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $categoriesList = $categoriesList . $category->category->translation_one->name ?? '';
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $vendor->categoriesList = $categoriesList;

                $vends[] = $vendor->id;
                if (($preferences) && ($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {
                    $vendor = $this->getVendorDistanceWithTime($vendor, $preferences,$latitude, $longitude);
                }
            }

            $on_sale_product_details = $this->vendorProducts($vends, $langId, $type, '', $clientCurrency);
            $new_product_details = $this->vendorProducts($vends, $langId, $type, 'is_new', $clientCurrency);
            $feature_product_details = $this->vendorProducts($vends, $langId, $type, 'is_featured', $clientCurrency);
            foreach ($new_product_details as  $new_product_detail) {
                $multiply = $new_product_detail->variant->first() ? $new_product_detail->variant->first()->multiplier : 1;
                $title = $new_product_detail->translation->first() ? $new_product_detail->translation->first()->title : $new_product_detail->sku;
                $image_url = $new_product_detail->media->first() ? $new_product_detail->media->first()->image->path['image_fit'] . '600/600' . $new_product_detail->media->first()->image->path['image_path'] : '';
                $new_products[] = array(
                    'image_url' => $image_url,
                    'sku' => $new_product_detail->sku,
                    'title' => $title,
                    'url_slug' => $new_product_detail->url_slug,
                    'averageRating' => number_format($new_product_detail->averageRating, 1, '.', ''),
                    'inquiry_only' => $new_product_detail->inquiry_only,
                    'vendor_name' => $new_product_detail->vendor ? $new_product_detail->vendor->name : '',
                    'price' => number_format($new_product_detail->variant->first()->price * $multiply, 2, '.', ''),
                    'category' => ($new_product_detail->category->categoryDetail->translation->first()) ? $new_product_detail->category->categoryDetail->translation->first()->name : $new_product_detail->category->categoryDetail->slug
                );
            }
            foreach ($feature_product_details as  $feature_product_detail) {
                $multiply = $feature_product_detail->variant->first() ? $feature_product_detail->variant->first()->multiplier : 1;
                $title = $feature_product_detail->translation->first() ? $feature_product_detail->translation->first()->title : $feature_product_detail->sku;
                $image_url = $feature_product_detail->media->first() ? $feature_product_detail->media->first()->image->path['image_fit'] . '600/600' . $feature_product_detail->media->first()->image->path['image_path'] : '';
                $feature_products[] = array(
                    'image_url' => $image_url,
                    'sku' => $feature_product_detail->sku,
                    'title' => $title,
                    'url_slug' => $feature_product_detail->url_slug,
                    'averageRating' => number_format($feature_product_detail->averageRating, 1, '.', ''),
                    'inquiry_only' => $feature_product_detail->inquiry_only,
                    'vendor_name' => $feature_product_detail->vendor ? $feature_product_detail->vendor->name : '',
                    'price' => number_format($feature_product_detail->variant->first()->price * $multiply, 2, '.', ''),
                    'category' => ($feature_product_detail->category->categoryDetail->translation->first()) ? $feature_product_detail->category->categoryDetail->translation->first()->name : $feature_product_detail->category->categoryDetail->slug
                );
            }
            foreach ($on_sale_product_details as  $on_sale_product_detail) {
                $multiply = $on_sale_product_detail->variant->first() ? $on_sale_product_detail->variant->first()->multiplier : 1;
                $title = $on_sale_product_detail->translation->first() ? $on_sale_product_detail->translation->first()->title : $on_sale_product_detail->sku;
                $image_url = $on_sale_product_detail->media->first() ? $on_sale_product_detail->media->first()->image->path['image_fit'] . '600/600' . $on_sale_product_detail->media->first()->image->path['image_path'] : '';
                $on_sale_products[] = array(
                    'image_url' => $image_url,
                    'sku' => $on_sale_product_detail->sku,
                    'title' => $title,
                    'url_slug' => $on_sale_product_detail->url_slug,
                    'averageRating' => number_format($on_sale_product_detail->averageRating, 1, '.', ''),
                    'inquiry_only' => $on_sale_product_detail->inquiry_only,
                    'vendor_name' => $on_sale_product_detail->vendor ? $on_sale_product_detail->vendor->name : '',
                    'price' => number_format($on_sale_product_detail->variant->first()->price * $multiply, 2, '.', ''),
                    'category' => ($on_sale_product_detail->category->categoryDetail->translation->first()) ? $on_sale_product_detail->category->categoryDetail->translation->first()->name : $on_sale_product_detail->category->categoryDetail->slug
                );
            }


            $isVendorArea = 0;
            $categories = $this->categoryNav($langId, $vends);
            $homeData['vendors'] = $vendorData;
            $homeData['categories'] = $categories;
            $homeData['reqData'] = $request->all();
            $homeData['on_sale_products'] = $on_sale_product_details;
            $homeData['new_products'] = $new_product_details;
            $homeData['featured_products'] = $feature_product_details;
            $homeData['brands'] = Brand::with(['translation' => function ($q) use ($langId) {
                $q->select('brand_id', 'title')->where('language_id', $langId);
            }])->select('id', 'image', 'image_banner')->where('status', '!=', $this->field_status)
                ->orderBy('position', 'asc')->get();
            $user_vendor_count = UserVendor::where('user_id', $user->id)->count();
            $homeData['is_admin'] = $user_vendor_count > 0 ? 1 : 0;
            return $this->successResponse($homeData);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    /** return dashboard content like categories, vendors, brands, products     */
    public function delivery_homepage(Request $request)
    {
        try {
            $homeData = [];
            $user = Auth::user();
            $lang_id = $user->language;
            $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                ->select('categories.id', 'categories.icon', 'categories.image', 'categories.slug', 'categories.parent_id', 'cts.name','categories.is_category_visible','categories.is_pickup_enabled','categories.is_dropoff_enabled','categories.is_schedule_enabled','categories.is_image_upload_enabled','categories.is_enabled', 'categories.warning_page_id', 'categories.template_type_id', 'types.title as redirect_to')
                ->distinct('categories.slug')
                ->where('is_category_visible', 1)
                ->where('cts.language_id', $lang_id)
                ->orderByRaw("FIELD(is_enabled,'1') DESC")
                ->leftjoin('types', 'types.id', 'categories.type_id')
                ->where('categories.id', '>', '1')
                ->whereNotNull('categories.type_id')
                ->orderBy('categories.position')
                ->get();
            $categories = $this->buildTree($categories->toArray());
            $homeData['categories'] = $categories;
            $homeData['reqData'] = $request->all();
            $homeData['vendors'] = [];
            $homeData['on_sale_products'] = [];
            $homeData['new_products'] = [];
            $homeData['featured_products'] = [];
            $homeData['brands'] = [];
            return $this->successResponse($homeData);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function vendorProducts($venderIds, $langId, $type, $where = '',$currency = '')
    {
        $products = Product::with([
            'category.categoryDetail.translation' => function ($q) use ($langId) {
                $q->where('category_translations.language_id', $langId);
            },
            'vendor' => function ($q) use ($type) {
                $q->where($type, 1);
            },
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');
        if ($where !== '') {
            $products = $products->where($where, 1);
        }
        $pndCategories = Category::where('type_id', 7)->pluck('id');
        if (is_array($venderIds)) {
            $products = $products->whereIn('vendor_id', $venderIds);
        }
        if ($pndCategories) {
            $products = $products->whereNotIn('category_id', $pndCategories);
        }
        $products = $products->where('is_live', 1)->take(10)->inRandomOrder()->get();
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = $currency ? $currency->doller_compare : 1;
                }
            }
        }
        return $products;
    }

    /** return product meta data for new products, featured products, onsale products     */
    public function productList($venderIds, $langId = 1, $currency = 147, $where = '')
    {
        $clientCurrency = ClientCurrency::where('currency_id', $currency)->first();
        $products = Product::with([
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
            ->where('is_live', 1);
        if ($where !== '') {
            $products = $products->where($where, 1);
        }
        if (is_array($venderIds) && count($venderIds) > 0) {
            $products = $products->whereIn('vendor_id', $venderIds);
        }
        $products = $products->get();
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = $clientCurrency->doller_compare;
                }
            }
        }
        return $products;
    }

    public function globalSearch(Request $request, $for = 'all', $dataId = 0)
    {
        
        try {
            $keyword = $request->keyword;
            $langId = Auth::user()->language;
            $curId = Auth::user()->language;
            $response = array();
            if ($for == 'all') {
                $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                    ->leftjoin('types', 'types.id', 'categories.type_id')
                    ->select('categories.id', 'categories.icon', 'categories.image', 'categories.slug', 'categories.parent_id', 'cts.name', 'categories.warning_page_id', 'categories.template_type_id', 'types.title as redirect_to')
                    ->where('categories.id', '>', '1')
                    ->where('categories.is_visible', 1)
                    ->where('categories.status', '!=', 2)
                    ->where('categories.is_core', 1)
                    ->whereNotIn('categories.slug', ['recovery', 'delivery'])
                    ->where('cts.language_id', $langId)
                    ->where(function ($q) use ($keyword) {
                        $q->where('cts.name', ' LIKE', '%' . $keyword . '%')
                            ->orWhere('categories.slug', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('cts.trans-slug', 'LIKE', '%' . $keyword . '%');
                    })->orderBy('categories.parent_id', 'asc')
                    ->orderBy('categories.position', 'asc')->get();
                foreach ($categories as $category) {
                    $category->response_type = 'category';
                    $category->image_url = $category->image['proxy_url'] . '80/80' . $category->image['image_path'];
                    $response[] = $category;
                }

                $brands = Brand::join('brand_translations as bt', 'bt.brand_id', 'brands.id')
                    ->select('brands.id', 'bt.title  as dataname', 'image')
                    ->where('bt.title', 'LIKE', '%' . $keyword . '%')
                    ->where('brands.status', '!=', '2')
                    ->where('bt.language_id', $langId)
                    ->orderBy('brands.position', 'asc')->get();
                foreach ($brands as $brand) {
                    $brand->response_type = 'brand';
                    $brand->image_url = $brand->image['proxy_url'] . '80/80' . $brand->image['image_path'];
                    $response[] = $brand;
                }

                $vendors = Vendor::select('id', 'name  as dataname', 'logo', 'slug', 'address');
                $vendors = $vendors->where(function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%$keyword%")->orWhere('address', 'LIKE', '%' . $keyword . '%');
                })->where('status', 1)->whereHas('vendorCategories.category', function ($query) {
                    $query->whereNotIn('slug', ['recovery', 'delivery']);
                })->get();
               
                foreach ($vendors as $vendor) {
                    $vendor->response_type = 'vendor';
                    $vendor->image_url = $vendor->logo['proxy_url'] . '80/80' . $vendor->logo['image_path'];
                    $response[] = $vendor;
                }
                // $vendors  = Vendor::select('id', 'name  as dataname', 'address')->where(function ($q) use ($keyword) {
                //         $q->where('name', ' LIKE', '%' . $keyword . '%')->orWhere('address', 'LIKE', '%' . $keyword . '%');
                //     })->where('vendors.status', '!=', '2')->get();
                // foreach ($vendors as $vendor) {
                //     $vendor->response_type = 'vendor';
                //     // $response[] = $vendor;
                // }
                $products = Product::with(['category.categoryDetail.translation' => function ($q) use ($langId) {
                    $q->where('category_translations.language_id', $langId);
                }, 'media','vendor'])->join('product_translations as pt', 'pt.product_id', 'products.id')
                    ->select('products.id', 'products.sku', 'pt.title  as dataname', 'pt.body_html', 'pt.meta_title', 'pt.meta_keyword', 'pt.meta_description','vendor_id')
                    ->where('pt.language_id', $langId)
                    ->where(function ($q) use ($keyword) {
                        $q->where('products.sku', ' LIKE', '%' . $keyword . '%')->orWhere('products.url_slug', 'LIKE', '%' . $keyword . '%')->orWhere('pt.title', 'LIKE', '%' . $keyword . '%');
                    })->where('products.is_live', 1)->whereNotIn('products.category_id', function ($query) {
                        $query->select('categories.id')
                              ->from('categories')
                              ->whereIn('categories.slug', ['recovery', 'delivery']);
                    })->whereNull('deleted_at')->groupBy('products.id')->get();
                foreach ($products as $product) {
                    $product->response_type = 'product';
                    $product->image_url = ($product->media->isNotEmpty()) ? $product->media->first()->image->path['image_fit'] . '300/300' . $product->media->first()->image->path['image_path'] : '';
                    $response[] = $product;
                }
                return $this->successResponse($response);
            } else {
                $products = Product::join('product_translations as pt', 'pt.product_id', 'products.id')
                    ->select('products.id', 'products.sku', 'pt.title', 'pt.body_html', 'pt.meta_title', 'pt.meta_keyword', 'pt.meta_description')
                    ->where('pt.language_id', $langId)->whereNotIn('products.category_id', function ($query) {
                        $query->select('categories.id')
                              ->from('categories')
                              ->whereIn('categories.slug', ['recovery', 'delivery']);
                    })
                    ->where(function ($q) use ($keyword) {
                        $q->where('products.sku', ' LIKE', '%' . $keyword . '%')
                            ->orWhere('products.url_slug', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('pt.title', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('pt.body_html', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('pt.meta_title', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('pt.meta_keyword', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('pt.meta_description', 'LIKE', '%' . $keyword . '%');
                    });
                if ($for == 'category') {
                    $prodIds = array();
                    $productCategory = ProductCategory::select('product_id')->where('category_id', $dataId)->distinct()->get();
                    if ($productCategory) {
                        foreach ($productCategory as $key => $value) {
                            $prodIds[] = $value->product_id;
                        }
                    }
                    $products = $products->whereIn('products.id', $prodIds);
                }
                if ($for == 'vendor') {
                    $products = $products->where('products.vendor_id', $dataId);
                }
                if ($for == 'brand') {
                    $products = $products->where('products.brand_id', $dataId);
                }
                $products = $products->where('products.is_live', 1)->whereNotIn('products.category_id', function ($query) {
                    $query->select('categories.id')
                          ->from('categories')
                          ->whereIn('categories.slug', ['recovery', 'delivery']);
                })->whereNull('deleted_at')->groupBy('products.id')->get();
                foreach ($products as $product) {
                    $product->response_type = 'product';
                    $response[] = $product;
                }
            }
            return $this->successResponse($response);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function contactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'email' => 'required',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                return $this->errorResponse($error_value[0], 400);
            }
        }
        $client = Client::select('id', 'name', 'email', 'phone_number', 'contact_email', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from','android_app_link','ios_link')->where('id', '>', 0)->first();
        $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
        
        $superAdmin = User::where('is_superadmin', 1)->first();
        if ($superAdmin) {
            try {

                $email_template_content = '';
                $email_template = EmailTemplate::where('id', 11)->first();
                $mail_from = $data->mail_from;
                $sendto = $client->contact_email ? $client->contact_email : $superAdmin->email;
                if ($email_template) {
                    $email_template_content = $email_template->content;
                    $email_template_content = str_ireplace("{customer_name}", ucwords($request->name), $email_template_content);
                    $email_template_content = str_ireplace("{phone_number}", ucwords($request->phone_number), $email_template_content);
                    $email_template_content = str_ireplace("{email}", ucwords($request->email), $email_template_content);
                    $email_template_content = str_ireplace("{message}", ucwords($request->message), $email_template_content);
                }
                $mail_data = [
                    'link' => "link",
                    'email' => $sendto,
                    'mail_from' => $mail_from,
                    'logo' => $client->logo['original'],
                    'subject' => $email_template->subject,
                    'email_template_content' => $email_template_content,
                ];
                dispatch(new \App\Jobs\SendOrderSuccessEmailJob($mail_data))->onQueue('verify_email');

                // Send acknowledgment email to customer using new EmailService
                // Try to get language from user if they're logged in, otherwise use session or default
                $languageId = null;
                if (Auth::check() && Auth::user()->language_id) {
                    $languageId = Auth::user()->language_id;
                } else {
                    $languageId = session()->get('customerLanguage', 1); // Default to English
                }
                
                // Use new EmailService for multi-language and RTL support
                $this->sendEmail(
                    $request->email,
                    13, // Acknowledgment email template ID
                    [
                        'customer_name' => ucwords($request->name),
                        'ack-email-banner' => asset("images/email/ack-email-banner.jpg")
                    ],
                    [],
                    $languageId,
                    true,
                    'verify_email'
                );
                
                return $this->successResponse('', 'Thank you for contacting us. We will get to you shortly');
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), $e->getCode());
            }
        } else {
            return $this->errorResponse('We are sorry for inconvenience. Please contact us later', 400);
        }
    }

    public function getLanguageDetails(Request $request)
    {
        try {
            // Assuming $request->lang holds something like "en" or "ar"
            $selectedLanguage = ClientLanguage::query()
            ->where('is_active', 1)
            // limit by the related Language row
            ->whereHas('language', fn ($q) =>
                $q->where('sort_code', $request->lang)
            )
            // pull back only the columns you need from Language
            ->with(['language:id,sort_code,name,is_rtl'])
            // grab the first (there should only be one)
            ->first()?->language;   // ->language gives you just the Language model


            //  Shape the response
            $data = [
                'langData'  => [
                    'welcome' => 'Welcome to our app!',
                    'logout'  => 'You have been logged out.',
                ],
                'selectedLanguage'=> $selectedLanguage,
            ];

            return $this->successResponse($data);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    public function getActiveLanguages()
    {
        try {
            // Get the languages with only required language fields
            $languages = ClientLanguage::with([
                    'language:id,sort_code,name,is_rtl'
                ])
                ->where('is_active', 1)
                ->orderByDesc('is_primary')
                ->get();

            // Transform the collection to include only the 'language' part
            $languageList = $languages->pluck('language')->filter(); // removes nulls if any

            return $this->successResponse($languageList);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
