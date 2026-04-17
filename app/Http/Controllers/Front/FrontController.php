<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\User;
use DB;
use App;
use Auth;
use Config;
use Session;
use Carbon\CarbonPeriod;
use DateTime;
use DateInterval;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client as TwilioClient;
use App\Models\{Client, Category, Product, ClientPreference,EmailTemplate, ClientCurrency, UserDevice, UserLoyaltyPoint, Wallet, UserSavedPaymentMethods, SubscriptionInvoicesUser,Country,UserAddress,CartProduct, Vendor, VendorCategory, ClientLanguage};
use App\Http\Traits\SendsEmails;
use Log;
class FrontController extends Controller
{
    use SendsEmails;
    
    private $field_status = 2;
    protected function sendSms($provider, $sms_key, $sms_secret, $sms_from, $to, $body){

        try{
            $client = new TwilioClient($sms_key, $sms_secret);
            $res = $client->messages->create($to, ['from' => $sms_from, 'body' => $body]);
            Log::info($res);
        }
        catch(\Exception $e){
            return '2';
        }
        return '1';
	}
    public function categoryNav($lang_id)
    {
       $preferences = Session::get('preferences');
       $primary = ClientLanguage::orderBy('is_primary','desc')->first();
       $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
       ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.parent_id', 'cts.name')->distinct('categories.id');
        $status = $this->field_status;
        if ($preferences) {
            if ((isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1)) {
                $vendors = (Session::has('vendors')) ? Session::get('vendors') : array();
                $categories = $categories->leftJoin('vendor_categories as vct', 'categories.id', 'vct.category_id')
                    ->where(function ($q1) use ($vendors, $status, $lang_id) {
                        $q1->whereIn('vct.vendor_id', $vendors)
                            ->where('vct.status', 1)
                            ->orWhere(function ($q2) {
                                $q2->whereIn('categories.type_id', [4,5,8]);
                            });
                    });
            }
        }
        $categories = $categories->where('categories.id', '>', '1')
            ->whereNotNull('categories.type_id')
            ->whereNotIn('categories.type_id', [7])
            ->where('categories.is_visible', 1)
            ->where('categories.status', '!=', $status)
            ->where('cts.language_id', $lang_id)
            ->where(function ($qrt) use($lang_id,$primary){
                $qrt->where('cts.language_id', $lang_id)->orWhere('cts.language_id',$primary->language_id);
             })
            ->whereNull('categories.vendor_id')
            ->orderBy('categories.position', 'asc')
            ->orderBy('categories.parent_id', 'asc')->groupBy('id')->get();
        if ($categories) {
            $categories = $this->buildTree($categories->toArray());
        }


        return $categories;
    }

    public function buildTree($elements, $parentId = 1)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function getChildCategoriesForVendor($category_id, $langId=1, $vid=0)
    {
        $category_list = array();

        $categories = Category::with(['translation' => function($q) use($langId){
                $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                ->where('category_translations.language_id', $langId);
            }, 'childs'])
            ->select('id', 'icon', 'image', 'slug', 'type_id', 'can_add_products', 'parent_id')
            ->where('parent_id', $category_id)->where('status', 1)->get();
        if($categories){
            foreach($categories as $cate){
                if($cate->childs){
                    foreach($cate->childs as $child){
                        $vendorCategory = VendorCategory::with(['category.translation' => function($q) use($langId){
                            $q->where('category_translations.language_id', $langId);
                        }])->where('vendor_id', $vid)->where('category_id', $child->id)->where('status', 1)->first();
                        if($vendorCategory){
                            $category_list[] = $vendorCategory;
                        }
                        $this->getChildCategoriesForVendor($child->id, $langId, $vid);
                    }
                }

                $vendorCategory = VendorCategory::with(['category.translation' => function($q) use($langId){
                    $q->where('category_translations.language_id', $langId);
                }])->where('vendor_id', $vid)->where('category_id', $cate->id)->where('status', 1)->first();
                if($vendorCategory){
                    $category_list[] = $vendorCategory;
                }
                $this->getChildCategoriesForVendor($cate->id, $langId, $vid);
            }
        }
        return $category_list;
    }

    public function getServiceAreaVendors(){
        $latitude = Session::get('latitude');
        $longitude = Session::get('longitude');
        $vendorType = Session::get('vendorType');
        $serviceAreaVendors = Vendor::select('id');
        $vendors = [];
        if($vendorType){
            $serviceAreaVendors = $serviceAreaVendors->where($vendorType, 1);
        }
        $serviceAreaVendors = $serviceAreaVendors->whereHas('serviceArea', function($query) use($latitude, $longitude){
                $query->select('vendor_id')
                ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
            })
            ->where('status', 1)->get();

        if($serviceAreaVendors->isNotEmpty()){
            foreach($serviceAreaVendors as $value){
                $vendors[] = $value->id;
            }
        }
        Session::put('vendors', $vendors);
        return $vendors;
    }

    public function productList($vendorIds, $langId, $currency = 'USD', $where = '')
    {
        $products = Product::with([
            'category.categoryDetail.translation' => function ($q) use ($langId) {
                $q->where('category_translations.language_id', $langId);
            },
            'vendor',
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode')->orderBy('price');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');

        if ($where !== '') {
            $products = $products->where($where, 1);
        }
        // if(is_array($vendorIds) && count($vendorIds) > 0){
            if (is_array($vendorIds)) {
                $products = $products->whereIn('vendor_id', $vendorIds);
            }
            $products = $products->where('is_live', 1)->take(6)->get();
        // pr($products->toArray());die;
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                $value->averageRating = number_format($value->averageRating, 1, '.', '');
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = Session::get('currencyMultiplier');
                }

                $value->category_name = $value->category->categoryDetail->translation->first() ? $value->category->categoryDetail->translation->first()->name :  $value->category->slug;
            }
        }
        return $products;
    }

    public function metaProduct($langId, $multiplier, $for = 'related', $productArray = []){
        if(empty($productArray)){
            return $productArray;
        }
        $productIds = array();
        foreach ($productArray as $key => $value) {
            if($for == 'related'){
                $productIds[] = $value->related_product_id;
            }
            if($for == 'upSell'){
                $productIds[] = $value->upsell_product_id;
            }
            if($for == 'crossSell'){
                $productIds[] = $value->cross_product_id;
            }
        }
        $products = Product::with([
                        'category.categoryDetail.translation' => function ($q) use ($langId) {
                            $q->where('category_translations.language_id', $langId);
                        },
                        'vendor', 'media' => function($q){
                            $q->groupBy('product_id');
                        }, 'media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'averageRating', 'url_slug', 'is_new', 'is_featured', 'vendor_id', 'inquiry_only')
                    ->whereIn('id', $productIds);
        $products = $products->get();
        if(!empty($products)){
            foreach ($products as $key => $value) {
                if($value->is_new == 1){
                    $value->product_type = 'New Product';
                }elseif($value->is_featured == 1){
                    $value->product_type = 'Featured Product';
                }else{
                    $value->product_type ='On Sale';
                }
                $value->product_media = $value->media ? $value->media->first() : NULL;
                $value->vendor_name = $value->vendor ? $value->vendor->name : '';
                $value->translation_title = (!empty($value->translation->first())) ? $value->translation->first()->title : $value->sku;
                $value->translation_description = (!empty($value->translation->first())) ? $value->translation->first()->body_html : $value->sku;
                $value->variant_multiplier = $multiplier ? $multiplier : 1;
                $value->variant_price = (!empty($value->variant->first())) ? number_format(($value->variant->first()->price * $multiplier),2,'.','') : 0;
                $value->averageRating = number_format($value->averageRating, 1, '.', '');
                $value->category_name = $value->category->categoryDetail->translation->first()->name;
                // foreach ($value->variant as $k => $v) {
                //     $value->variant[$k]->multiplier = $multiplier;
                // }
            }
        }
        return $products;
    }

    public function setMailDetail($mail_driver, $mail_host, $mail_port, $mail_username, $mail_password, $mail_encryption)
    {
        $config = array(
            'driver' => $mail_driver,
            'host' => $mail_host,
            'port' => $mail_port,
            'encryption' => $mail_encryption,
            'username' => $mail_username,
            'password' => $mail_password,
            'sendmail' => '/usr/sbin/sendmail -bs',
            'pretend' => false,
        );

        Config::set('mail', $config);
        $app = App::getInstance();
        $app->register('Illuminate\Mail\MailServiceProvider');
        return '1';

        // return '2';
    }

    /**     * check if cookie already exist     */
    public function checkCookies($userid)
    {
        if (isset($_COOKIE['uuid'])) {
            $userFind = User::where('system_id', Auth::user()->system_user)->first();
            if ($userFind) {
                $cart = Cart::where('user_id', $userFind->id)->first();
                if ($cart) {
                    $cart->user_id = $userid;
                    $cart->save();
                }
                $userFind->delete();
            }
            setcookie("uuid", "", time() - 3600);
            return redirect()->route('user.checkout');
        }
    }

    /**     * check if cookie already exist     */
    public function userMetaData($userid, $device_type = 'web', $device_token = 'web')
    {
        $device = UserDevice::where('user_id', $userid)->first();
        if (!$device) {
            $user_device[] = [
                'user_id' => $userid,
                'device_type' => $device_type,
                'device_token' => $device_token,
                'access_token' => ''
            ];
            UserDevice::insert($user_device);
        }
        $loyaltyPoints = UserLoyaltyPoint::where('user_id', $userid)->first();
        if (!$loyaltyPoints) {
            $loyalty[] = [
                'user_id' => $userid,
                'points' => 0
            ];
            UserLoyaltyPoint::insert($loyalty);
        }
        $wallet = Wallet::where('user_id', $userid)->first();
        if (!$wallet) {
            $walletData[] = [
                'user_id' => $userid,
                'type' => 1,
                'balance' => 0,
                'card_id' => $this->randomData('wallets', 6, 'card_id'),
                'card_qr_code' => $this->randomBarcode('wallets'),
                'meta_field' => '',
            ];

            Wallet::insert($walletData);
        }
        return 1;
    }

    /* Create random and unique client code*/
    public function randomData($table, $digit, $where)
    {
        $random_string = substr(md5(microtime()), 0, $digit);
        // after creating, check if string is already used

        while (\DB::table($table)->where($where, $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, $digit);
        }
        return $random_string;
    }

    public function randomBarcode($table)
    {
        $barCode = substr(md5(microtime()), 0, 14);
        // $number = mt_rand(1000000000, 9999999999);

        while (\DB::table($table)->where('card_qr_code', $barCode)->exists()) {
            $barCode = substr(md5(microtime()), 0, 14);
        }
        return $barCode;
    }

    /* Save user payment method */
    public function saveUserPaymentMethod($request)
    {
        $payment_method = new UserSavedPaymentMethods;
        $payment_method->user_id = Auth::user()->id;
        $payment_method->payment_option_id = $request->payment_option_id;
        $payment_method->card_last_four_digit = $request->card_last_four_digit;
        $payment_method->card_expiry_month = $request->card_expiry_month;
        $payment_method->card_expiry_year = $request->card_expiry_year;
        $payment_method->customerReference = ($request->has('customerReference')) ? $request->customerReference : NULL;
        $payment_method->cardReference = ($request->has('cardReference')) ? $request->cardReference : NULL;
        $payment_method->save();
    }

    /* Get Saved user payment method */
    public function getSavedUserPaymentMethod($request)
    {
        $saved_payment_method = UserSavedPaymentMethods::where('user_id', Auth::user()->id)
                        ->where('payment_option_id', $request->payment_option_id)->first();
        return $saved_payment_method;
    }

    public function sendMailToSubscribedUsers(){
        $after7days = Carbon::now()->addDays(7)->toDateString();
        $now = Carbon::now()->toDateString();
        $active_subscriptions = SubscriptionInvoicesUser::with(['plan', 'features.feature', 'user'])
                                ->whereBetween('end_date', [$now, $after7days])
                                ->whereNull('cancelled_at')->get();
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();

        foreach($active_subscriptions as $subscription){
            if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
                $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
                $client_name = $client->name;
                $mail_from = $data->mail_from;
                $sendto = $subscription->user->email;
                try{
                    $data = [
                        'customer_name' => $subscription->user->name,
                        'code_text' => '',
                        'logo' => $client->logo['original'],
                        'frequency' => $subscription->frequency,
                        'end_date' => $subscription->end_date,
                        'link'=> "http://local.myorder.com/user/subscription/select/".$subscription->plan->slug,
                    ];
                    Mail::send('email.notifyUserSubscriptionBilling', ['mailData'=>$data],
                    function ($message) use($sendto, $client_name, $mail_from) {
                        $message->from($mail_from, $client_name);
                        $message->to($sendto)->subject('Upcoming Subscription Billing');
                    });
                    $response['send_email'] = 1;
                }
                catch(\Exception $e){
                    return response()->json(['data' => $e->getMessage()]);
                }
            }
        }
    }

    /* Get vendor rating from its products rating */
    public function vendorRating($vendorProducts)
    {
        $vendor_rating = 0;
        if($vendorProducts->isNotEmpty()){
            $product_rating = 0;
            $product_count = 0;
            foreach($vendorProducts as $product){
                if($product->averageRating > 0){
                    $product_rating = $product_rating + $product->averageRating;
                    $product_count++;
                }
            }
            if($product_count > 0){
                $vendor_rating = $product_rating / $product_count;
            }
        }
        return number_format($vendor_rating, 1, '.', '');
    }

    /* doller compare amount */
    public function getDollarCompareAmount($amount, $customerCurrency='')
    {
        $customerCurrency = Session::has('customerCurrency') ? Session::get('customerCurrency') : ( (!empty($customerCurrency)) ? $customerCurrency : '' );
        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        if(empty($customerCurrency)){
            $clientCurrency = $primaryCurrency;
        }else{
            $clientCurrency = ClientCurrency::where('currency_id', $customerCurrency)->first();
        }
        $divider = (empty($clientCurrency->doller_compare) || $clientCurrency->doller_compare < 0) ? 1 : $clientCurrency->doller_compare;
        $amount = ($amount / $divider) * $primaryCurrency->doller_compare;
        $amount = number_format($amount, 2);
        return $amount;
    }

    public function setVendorType($type = ''){
        if(empty($type)){
           $type = 'delivery';
        }
        Session::put('vendorType', $type);
        return Session::get('vendorType');
    }


    // get cart data in on demand product listing page
    public function getCartOnDemand($request)
    {
        $cartData = [];
        $user = Auth::user();
        $countries = Country::get();
        $langId = Session::get('customerLanguage');
        $guest_user = true;
        if ($user) {
            $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('user_id', $user->id)->first();
            $addresses = UserAddress::where('user_id', $user->id)->get();
            $guest_user = false;
        } else {
            $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
            $addresses = collect();
        }
        if ($cart) {
            $cartData = CartProduct::where('status', [0, 1])->where('cart_id', $cart->id)->orderBy('created_at', 'asc')->get();
        }
        $navCategories = $this->categoryNav($langId);
        $subscription_features = array();
        if ($user) {
            $now = Carbon::now()->toDateTimeString();
            $user_subscription = SubscriptionInvoicesUser::with('features')
                ->select('id', 'user_id', 'subscription_id')
                ->where('user_id', $user->id)
                ->where('end_date', '>', $now)
                ->orderBy('end_date', 'desc')->first();
            if ($user_subscription) {
                foreach ($user_subscription->features as $feature) {
                    $subscription_features[] = $feature->feature_id;
                }
            }
        }

        $user = Auth::user();
        $timezone = $user->timezone ?? 'Asia/Kolkata';

        $start_date = new DateTime("now", new  DateTimeZone($timezone) );
        $start_date =  $start_date->format('Y-m-d');
        $end_date = Date('Y-m-d', strtotime('+13 days'));

        $start_time = new DateTime("now", new  DateTimeZone($timezone) );
        $start_time = $start_time->format('Y-m-d H:m');
        $end_time = date('Y-m-d 23:59');
        $period = CarbonPeriod::create($start_date, $end_date);
        $time_slots = $this->SplitTime($start_time, $end_time, "60");
        return ['time_slots' => $time_slots,'period' => $period,'cartData' => $cartData, 'addresses' => $addresses, 'countries' => $countries, 'subscription_features' => $subscription_features, 'guest_user'=>$guest_user];
    }


    /////////// ***************    get all time slots *******************************  /////////////////////
    function SplitTime($StartTime, $EndTime, $Duration="30"){


        $ReturnArray = array ();// Define output
        if(date ("i", strtotime($StartTime)) > 30)
        $startwith = 00;
        else
        $startwith = 30;
        $StartTime = date ("Y-m-d G", strtotime($StartTime));
        $StartTime = $StartTime.":".$startwith;
        $StartTime    = strtotime ($StartTime); //Get Timestamp
        $EndTime      = strtotime ($EndTime); //Get Timestamp
        $AddMins  = $Duration * 30;


        while ($StartTime <= $EndTime) //Run loop
        {
            $ReturnArray[] = date ("G:i", $StartTime);
            $StartTime += $AddMins; //Endtime check
        }
        return $ReturnArray;
    }

    function getVendorDistanceWithTime($vendor, $preferences,$userLat='', $userLong=''){
        if(($preferences) && ($preferences->is_hyperlocal == 1)){
            if( (empty($userLat)) && (empty($userLong)) ){
                $userLat = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_latitude) : 0;
                $userLong = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_longitude) : 0;
            }

            $lat1   = $userLat;
            $long1  = $userLong;
            $lat2   = $vendor->latitude;
            $long2  = $vendor->longitude;
            if($lat1 && $long1 && $lat2 && $long2){
                $distance_unit = (!empty($preferences->distance_unit_for_time)) ? $preferences->distance_unit_for_time : 'kilometer';
                $unit_abbreviation = ($distance_unit == 'mile') ? 'miles' : 'km';
                $distance_to_time_multiplier = ($preferences->distance_to_time_multiplier > 0) ? $preferences->distance_to_time_multiplier : 2;
                $distance = $this->calulateDistanceLineOfSight($lat1, $long1, $lat2, $long2, $distance_unit);
                $vendor->lineOfSightDistance = number_format($distance, 1, '.', '') .' '. $unit_abbreviation;
                $vendor->timeofLineOfSightDistance = number_format(floatval($vendor->order_pre_time), 0, '.', '') + number_format(($distance * $distance_to_time_multiplier), 0, '.', ''); // distance is multiplied by distance time multiplier to calculate travel time
            }else{
                $vendor->lineOfSightDistance = 0;
                $vendor->timeofLineOfSightDistance = 0;
            }
        }
        return $vendor;
    }

    // Find distance between two lat long points
    function calulateDistanceLineOfSight($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          $unit = strtolower($unit);

          if ($unit == "kilometer") {
            return ($miles * 1.609344);
          } else if ($unit == "nautical mile") {
            return ($miles * 0.8684);
          } else {
            return $miles;
          }
        }
    }

    public function formattedOrderETA($minutes, $order_vendor_created_at, $scheduleTime=''){
        $d = floor ($minutes / 1440);
        $h = floor (($minutes - $d * 1440) / 60);
        $m = $minutes - ($d * 1440) - ($h * 60);
        // return (($d > 0) ? $d.' days ' : '') . (($h > 0) ? $h.' hours ' : '') . (($m > 0) ? $m.' minutes' : '');

        if($scheduleTime != ''){
            $datetime = Carbon::parse($scheduleTime)->setTimezone(Auth::user()->timezone)->toDateTimeString();
        }else{
            $datetime = Carbon::parse($order_vendor_created_at)->setTimezone(Auth::user()->timezone)->addMinutes($minutes)->toDateTimeString();
        }

        if(Carbon::parse($datetime)->isToday()){
            $format = 'h:i A';
        }else{
            $format = 'M d, Y h:i A';
        }
        // $time = convertDateTimeInTimeZone($datetime, Auth::user()->timezone, $format);
        $time = Carbon::parse($datetime)->format($format);
        return $time;
    }
    public function sendmailtest(){

        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
//         echo "<pre>";
// print_r($data->toArray());
// exit();

        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
                $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
                $client_name = $client->name;
                $mail_from = $data->mail_from;
                $sendto = "harbans.singh@codebrewinnovations.com";
                try{
                    $data = [
                        'customer_name' => "harbans",
                        'code_text' => '',
                        'logo' => $client->logo['original'],
                        'frequency' => 'asd',
                        'end_date' => "asd",
                        'link'=> "http://local.myorder.com/user/subscription/select/",
                    ];
                     $mail=   Mail::send('email.notifyUserSubscriptionBilling', ['mailData'=>$data],
                        function ($message) use($sendto, $client_name, $mail_from) {
                            $message->from($mail_from, $client_name);
                            $message->to($sendto)->subject('Upcoming Subscription Billing');
                        });
//                                 echo "<pre>";
// print_r($mail);
// exit();
                        $response['send_email'] = 1;
                }
                catch(\Exception $e){
                    return response()->json(['data' => $e->getMessage()]);
                }
            }

    }
}
