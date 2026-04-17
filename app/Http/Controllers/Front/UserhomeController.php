<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Front\FrontController;
use Illuminate\Contracts\Session\Session as SessionSession;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, VendorCategory, ClientCurrency,Client, ClientPreference, DriverRegistrationDocument, HomePageLabel, Page, VendorRegistrationDocument, Language, OnboardSetting, CabBookingLayout, WebStylingOption, SubscriptionInvoicesVendor, Order, OrderStatusOption, VendorOrderStatus, DeliveryCart, DeliveryCartTasks};
use Illuminate\Contracts\View\View;
use Illuminate\View\View as ViewView;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class UserhomeController extends FrontController
{
    use ApiResponser;
    private $field_status = 2;

    public function setTheme(Request $request)
    {
        if ($request->theme_color == "dark") {
            Session::put('config_theme', $request->theme_color);
        } else {
            Session::forget('config_theme');
        }
    }
    public function getConfig()
    {
        $client_preferences = ClientPreference::first();
        return response()->json(['success' => true, 'client_preferences' => $client_preferences]);
        dd("neskjbf");
    }

    public function getLastMileTeams()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $unique = Auth::user()->code;
                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->delivery_service_key,
                        'shortcode' => $dispatch_domain->delivery_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->delivery_service_key_url;
                $res = $client->get($url . '/api/get-all-teams');
                $response = json_decode($res->getBody(), true);
                if ($response && $response['message'] == 'success') {
                    return $response['teams'];
                }
            }
        } catch (\Exception $e) {
        }
    }

    public function getAgentTags()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $unique = Auth::user()->code;
                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->delivery_service_key,
                        'shortcode' => $dispatch_domain->delivery_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->delivery_service_key_url;
                $res = $client->get($url . '/api/get-all-teams');
                $response = json_decode($res->getBody(), true);
                if ($response && $response['message'] == 'success') {
                    return $response['teams'];
                }
            }
        } catch (\Exception $e) {
        }
    }

    public function checkIfLastMileDeliveryOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }

    public function driverDocuments()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileDeliveryOn();
            $url = $dispatch_domain->delivery_service_key_url;
            $endpoint =$url . "/api/send-documents";
            // $endpoint = "http://192.168.99.177:8006/api/send-documents";
            // $dispatch_domain = $this->checkIfLastMileDeliveryOn();
           // $dispatch_domain->delivery_service_key_code = '1da2e9';
           // $dispatch_domain->delivery_service_key = 'TMJdbQlNWkYl1JzMONzRgF4zQFuP8s';
            $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->delivery_service_key, 'shortcode' => $dispatch_domain->delivery_service_key_code]]);

            $response = $client->post($endpoint);
            $response = json_decode($response->getBody(), true);
            return json_encode($response['data']);
        } catch (\Exception $e) {
            $data = [];
            $data['status'] = 400;
            $data['message'] =  $e->getMessage();
            return $data;
        }
    }

    public function driverSignup()
    {
        $user = Auth::user();
        $language_id = Session::get('customerLanguage');
        $client_preferences = ClientPreference::first();
        $navCategories = $this->categoryNav($language_id);
        $client = Auth::user();
        // $ClientPreference = ClientPreference::where('client_code', $client->code)->first();
        // $preference = $ClientPreference ? $ClientPreference : new ClientPreference();
        $page_detail = Page::with(['translations' => function ($q) {
            $q->where('language_id', session()->get('customerLanguage'));
        }])->where('slug', 'driver-registration')->firstOrFail();
        $last_mile_teams = [];

        $tag = [];

        // if (isset($preference) && $preference->need_delivery_service == '1') {
        //     $last_mile_teams = $this->getLastMileTeams();
        // }
        $showTag = implode(',', $tag);
        $driver_registration_documents = json_decode($this->driverDocuments());
        $transport_types = $this->buildTransportTypesForDriverRegistration();
        $set_template = WebStylingOption::where('is_selected', 1)->first();
        return view('frontend.driver-registration', compact('page_detail', 'navCategories', 'user', 'showTag', 'driver_registration_documents', 'transport_types', 'set_template'));
    }
    public function transportTypes()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileDeliveryOn();
            if (!$dispatch_domain) {
                return json_encode([]);
            }
            $url = $dispatch_domain->delivery_service_key_url;
            $endpoint = $url . '/api/get-transport-types';
            $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->delivery_service_key, 'shortcode' => $dispatch_domain->delivery_service_key_code]]);

            $response = $client->post($endpoint);
            $response = json_decode($response->getBody(), true);
            $data = $response['data'] ?? [];
            $rows = $this->normalizeTransportTypesPayload($data);

            return json_encode(array_values($rows));
        } catch (\Exception $e) {
            Log::warning('transportTypes: ' . $e->getMessage());

            return json_encode([]);
        }
    }

    /**
     * Build transport rows for driver registration: stable list shape + resolved image URLs.
     */
    protected function buildTransportTypesForDriverRegistration(): array
    {
        $raw = json_decode($this->transportTypes(), true);
        if (!is_array($raw)) {
            return [];
        }
        $dispatchPrefs = $this->checkIfLastMileDeliveryOn();
        $items = [];
        foreach ($raw as $row) {
            if (!is_array($row) && !is_object($row)) {
                continue;
            }
            $row = (array) $row;
            $obj = (object) $row;
            if (empty($obj->name) && !empty($obj->title)) {
                $obj->name = $obj->title;
            }
            $path = $obj->icon ?? $obj->image ?? '';
            $pathStr = is_string($path) ? $path : '';
            $obj->resolved_icon = $this->resolveTransportTypeIconUrl($pathStr, $dispatchPrefs);
            $activeRaw = $obj->icon_active ?? $obj->selected_icon ?? $obj->icon_blue ?? null;
            if (is_string($activeRaw) && trim($activeRaw) !== '') {
                $obj->resolved_icon_active = $this->resolveTransportTypeIconUrl(trim($activeRaw), $dispatchPrefs);
            } else {
                $bluePath = $this->deriveBlueVariantIconPath($pathStr);
                $obj->resolved_icon_active = $bluePath !== null && $bluePath !== ''
                    ? $this->resolveTransportTypeIconUrl($bluePath, $dispatchPrefs)
                    : '';
            }
            $items[] = $obj;
        }

        return $items;
    }

    /**
     * Build "selected" asset name: van.png -> van_blue.png (same convention as add-agent modal).
     */
    protected function deriveBlueVariantIconPath(string $icon): ?string
    {
        $icon = trim($icon);
        if ($icon === '') {
            return null;
        }
        if (preg_match('/_blue\.(png|jpe?g|gif|webp)(\?|#|$)/i', $icon)) {
            return null;
        }
        if (preg_match('#^https?://#i', $icon)) {
            $parts = parse_url($icon);
            $path = $parts['path'] ?? '';
            $newPath = preg_replace('/(\.(png|jpe?g|gif|webp))$/i', '_blue$1', $path);
            if ($newPath === null || $newPath === $path) {
                return null;
            }
            $scheme = $parts['scheme'] ?? 'https';
            $host = $parts['host'] ?? '';
            if ($host === '') {
                return null;
            }
            $port = isset($parts['port']) ? ':' . $parts['port'] : '';
            $query = isset($parts['query']) ? '?' . $parts['query'] : '';
            $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

            return $scheme . '://' . $host . $port . $newPath . $query . $fragment;
        }
        $new = preg_replace('/(\.(png|jpe?g|gif|webp))$/i', '_blue$1', $icon);

        return ($new !== null && $new !== $icon) ? $new : null;
    }

    /**
     * @param  \App\Models\ClientPreference|bool  $dispatchPrefs
     */
    protected function resolveTransportTypeIconUrl(string $icon, $dispatchPrefs): string
    {
        $icon = trim($icon);
        if ($icon === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $icon)) {
            return $icon;
        }
        // Path relative to this app's public/ (e.g. assets/icons/van.png)
        if ($icon !== '' && $icon[0] !== '/') {
            return asset($icon);
        }
        // Root-relative paths usually live on the dispatch API host (e.g. /storage/..., /images/...)
        if ($dispatchPrefs && !empty($dispatchPrefs->delivery_service_key_url)) {
            return rtrim($dispatchPrefs->delivery_service_key_url, '/') . $icon;
        }

        return asset(ltrim($icon, '/'));
    }

    /**
     * Unwrap transport types from common API shapes: nested types[], slug-keyed maps, or a single row.
     *
     * @param  mixed  $data
     * @return array<int, array<string, mixed>>
     */
    protected function normalizeTransportTypesPayload($data): array
    {
        if ($data === null || $data === '') {
            return [];
        }
        if (is_object($data)) {
            $data = json_decode(json_encode($data), true);
        }
        if (!is_array($data)) {
            return [];
        }

        if ($this->isIndexedListOfTransportRows($data)) {
            $out = [];
            foreach ($data as $row) {
                $out[] = is_array($row) ? $row : (array) $row;
            }

            return $out;
        }

        if ($this->looksLikeTransportRow($data)) {
            return [is_array($data) ? $data : (array) $data];
        }

        foreach (['types', 'transport_types', 'transportTypes', 'items', 'records', 'list', 'rows', 'data'] as $key) {
            if (empty($data[$key])) {
                continue;
            }
            $inner = $this->normalizeTransportTypesPayload($data[$key]);
            if ($inner !== []) {
                return $inner;
            }
        }

        // Slug-keyed map: { "van": {...}, "bike": {...} }
        $rows = [];
        foreach ($data as $v) {
            if ($this->looksLikeTransportRow($v)) {
                $rows[] = is_array($v) ? $v : (array) $v;
            }
        }

        return $rows;
    }

    protected function isIndexedListOfTransportRows(array $data): bool
    {
        if ($data === []) {
            return false;
        }
        $keys = array_keys($data);
        $n = count($keys);
        $expected = range(0, $n - 1);
        // JSON arrays use int keys; some APIs return objects with "0","1",… string keys.
        $keysMatchList = $keys === $expected || array_map('intval', $keys) === $expected;
        if (!$keysMatchList) {
            return false;
        }

        return $this->looksLikeTransportRow(reset($data));
    }

    protected function looksLikeTransportRow($row): bool
    {
        if (!is_array($row) && !is_object($row)) {
            return false;
        }
        $a = (array) $row;

        return isset($a['id']) || isset($a['name']) || isset($a['title']) || isset($a['icon']) || isset($a['image']);
    }

    public function checkIfLastMileOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getExtraPage(Request $request, $domain = '', $slug, $lang = null)
    {
        $user = Auth::user();
        $language_id = Session::get('customerLanguage');
        
        // If language parameter is provided in URL, validate it's a language code (2-3 lowercase letters)
        // and look it up by sort_code
        if ($lang && preg_match('/^[a-z]{2,3}$/i', $lang)) {
            $clientLanguage = ClientLanguage::whereHas('language', function($q) use ($lang) {
                $q->where('sort_code', strtolower($lang));
            })->where('is_active', 1)->first();
            
            if ($clientLanguage && $clientLanguage->language) {
                $language_id = $clientLanguage->language_id;
                // Update session with the selected language
                Session::put('customerLanguage', $language_id);
                // Also set applocale for LanguageSwitch middleware
                Session::put('applocale', $clientLanguage->language->sort_code);
                // Set Laravel app locale
                App::setLocale($clientLanguage->language->sort_code);
            }
        }
        
        $client_preferences = ClientPreference::first();
        $navCategories = $this->categoryNav($language_id);
        
        // Get the page by slug first
        $page_detail = Page::where('slug', $slug)->first();
        
        // If page not found, throw 404
        if (!$page_detail) {
            abort(404, 'Page not found');
        }
        
        // Load translations for the current language
        $page_detail->load(['translations' => function ($q) use ($language_id) {
            $q->where('language_id', $language_id);
        }]);
        
        // If no translation found for current language, try primary language
        if ($page_detail->translations->isEmpty()) {
            $primaryLang = ClientLanguage::where('is_primary', 1)->first();
            if ($primaryLang) {
                $page_detail->load(['translations' => function ($q) use ($primaryLang) {
                    $q->where('language_id', $primaryLang->language_id);
                }]);
            }
        }
        
        $set_template = WebStylingOption::where('is_selected', 1)->first();
        if ($page_detail->primary->type_of_form != 2) {
            $vendor_registration_documents = VendorRegistrationDocument::get();
            $categories = Category::where('is_vendor_register',1)->get();
            return view('frontend.extrapage', compact('page_detail', 'navCategories', 'client_preferences', 'user', 'vendor_registration_documents', 'categories','set_template'));
        } else {
            $tag = [];
            $showTag = implode(',', $tag);
            $client = Client::with('country')->first();
            $driver_registration_documents = json_decode($this->driverDocuments());
            $transport_types = $this->buildTransportTypesForDriverRegistration();
            return view('frontend.driver-registration', compact('page_detail', 'navCategories', 'user', 'showTag', 'driver_registration_documents','client','transport_types','set_template'));
        }
    }
    public function index(Request $request)
    {
        try {
            $home = array();
            $vendor_ids = array();
            if ($request->has('ref')) {
                session(['referrer' => $request->query('ref')]);
            }
            $latitude = Session::get('latitude');
            $longitude = Session::get('longitude');
            $curId = Session::get('customerCurrency');
            $preferences = Session::get('preferences');
            $langId = Session::get('customerLanguage');
            $client_config = Session::get('client_config');
            $selectedAddress = Session::get('selectedAddress');
            $navCategories = $this->categoryNav($langId);
            Session::put('navCategories', $navCategories);
            $clientPreferences = ClientPreference::first();
            $count = 0;
            if ($clientPreferences) {
                if ($clientPreferences->dinein_check == 1) {
                    $count++;
                }
                if ($clientPreferences->takeaway_check == 1) {
                    $count++;
                }
                if ($clientPreferences->delivery_check == 1) {
                    $count++;
                }
            }
            if ($preferences) {
                if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
                    $selectedAddress = $preferences->Default_location_name;
                    $latitude = $preferences->Default_latitude;
                    $longitude = $preferences->Default_longitude;
                    Session::put('latitude', $latitude);
                    Session::put('longitude', $longitude);
                    Session::put('selectedAddress', $selectedAddress);
                }
            }
            $banners = Banner::where('status', 1)->where('validity_on', 1)
                ->where(function ($q) {
                    $q->whereNull('start_date_time')->orWhere(function ($q2) {
                        $q2->whereDate('start_date_time', '<=', Carbon::now())
                            ->whereDate('end_date_time', '>=', Carbon::now());
                    });
                })->orderBy('sorting', 'asc')->with('category')->with('vendor')->get();


            $home_page_labels = CabBookingLayout::where('is_active', 1)->orderBy('order_by');

            if (isset($langId) && !empty($langId))
                $home_page_labels = $home_page_labels->with(['translations' => function ($q) use ($langId) {
                    $q->where('language_id', $langId);
                }]);

            $home_page_labels = $home_page_labels->get();

            if (count($home_page_labels) == 0)
                $home_page_labels = HomePageLabel::with('translations')->where('is_active', 1)->orderBy('order_by')->get();


            $only_cab_booking = OnboardSetting::where('key_value', 'home_page_cab_booking')->count();
            if ($only_cab_booking == 1)
                return Redirect::route('categoryDetail', 'cabservice');

            $home_page_pickup_labels = CabBookingLayout::with('translations')->where('is_active', 1)->orderBy('order_by')->get();

            $set_template = WebStylingOption::where('is_selected', 1)->first();

            if (isset($set_template)  && $set_template->template_id == 1)
                return view('frontend.home-template-one')->with(['home' => $home,  'count' => $count, 'homePagePickupLabels' => $home_page_pickup_labels, 'homePageLabels' => $home_page_labels, 'clientPreferences' => $clientPreferences, 'banners' => $banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
            if (isset($set_template)  && $set_template->template_id == 2)
                return view('frontend.home')->with(['home' => $home, 'count' => $count, 'homePagePickupLabels' => $home_page_pickup_labels, 'homePageLabels' => $home_page_labels, 'clientPreferences' => $clientPreferences, 'banners' => $banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
            else
                return view('frontend.home-default-template')->with(['banners' => $banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
        } catch (Exception $e) {
            pr($e->getCode());
            die;
        }
    }
    /**
     * Encrypt order ID
     */
    private function encryptOrderId($orderId)
    {
        return Crypt::encryptString($orderId);
    }

    /**
     * Decrypt order ID
     */
    private function decryptOrderId($encryptedOrderId)
    {
        try {
            return Crypt::decryptString($encryptedOrderId);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Helper to get dispatcher data once
    private function getDispatcherOrderData($order)
    {
        if ($order && $order->vendors && $order->vendors->first() && $order->vendors->first()->dispatch_traking_url) {
            $url = str_replace('order', 'order-details', $order->vendors->first()->dispatch_traking_url);
            $response = Http::get($url);
            if ($response->status() == 200) {
                return json_decode($response->body());
            }
        }
        return null;
    }

    /**
     * Fetch rider details (name, phone number) from dispatcher API for an order
     */
    private function getRiderDetailsFromDispatcher($dispatcherData = null)
    {
        $rider = [
            'name' => null,
            'phone_number' => null,
        ];
        if ($dispatcherData && isset($dispatcherData->order->driver_id) && $dispatcherData->order->driver_id != null && isset($dispatcherData->tasks[0]->task_status) && $dispatcherData->tasks[0]->task_status > 1) {
            $rider['name'] = $dispatcherData->order->name ?? null;
            $rider['phone_number'] = $dispatcherData->order->phone_number ?? null;
        }
        return $rider;
    }

    public function orderTracking(Request $request)
    {
        try {
            $order_id = $request->order_id ?? $request->route('orderId');
            // Decrypt if not numeric (assume encrypted)
            if (!empty($order_id) && !is_numeric($order_id)) {
                $order_id = $this->decryptOrderId($order_id);
            }
            // Validate order ID
            if (empty($order_id)) {
                // For error case, use primary language
                $langId = ClientLanguage::where('is_primary', 1)->value('language_id') ?? 1;
                $lang_detail = Language::where('id', $langId)->first();
                if ($lang_detail) {
                    App::setLocale($lang_detail->sort_code);
                }
                
                $error = [
                    'title' => __('Order ID Required'),
                    'message' => __('Please provide a valid order number to track your order.')
                ];
                
                // Get required variables for layout
                $navCategories = $this->categoryNav($langId);
                $set_template = WebStylingOption::where('is_selected', 1)->first();
                $client_preference_detail = ClientPreference::first();
                $client_head = Client::first();
                
                return view('frontend.order-tracking', compact('error', 'navCategories', 'set_template', 'client_preference_detail', 'client_head'));
            }

            // Fetch real order data with user
            $order = Order::with(['user', 'address', 'vendors.allStatus.OrderStatusOption', 'vendors'])->where('order_number', $order_id)->orWhere('id', $order_id)->first();

            if ($order == null) {
                // For error case, use primary language
                $langId = ClientLanguage::where('is_primary', 1)->value('language_id') ?? 1;
                $lang_detail = Language::where('id', $langId)->first();
                if ($lang_detail) {
                    App::setLocale($lang_detail->sort_code);
                }
                
                $navCategories = $this->categoryNav($langId);
                $set_template = WebStylingOption::where('is_selected', 1)->first();
                $client_preference_detail = ClientPreference::first();
                $client_head = Client::first();
                
                $error = [
                    'title' => __('Order Not Found'),
                    'message' => __('The order you are looking for could not be found. Please check your order number and try again.')
                ];
                
                return view('frontend.order-tracking', compact('error', 'navCategories', 'set_template', 'client_preference_detail', 'client_head'));
            }
            
            // Get language from user who placed the order
            $langId = $order->user->language_id ?? ($order->user->language ?? 1);
            
            // Set locale for translations
            $lang_detail = Language::where('id', $langId)->first();
            if ($lang_detail) {
                App::setLocale($lang_detail->sort_code);
            }
            
            // Get required variables for layout
            $navCategories = $this->categoryNav($langId);
            $set_template = WebStylingOption::where('is_selected', 1)->first();
            $client_preference_detail = ClientPreference::first();
            $client_head = Client::first();

            // Call dispatcher API ONCE
            $dispatcherData = $this->getDispatcherOrderData($order);
            // Get rider details from dispatcher
            $rider = $this->getRiderDetailsFromDispatcher($dispatcherData);

            // Get order data
            $orderData = [
                'order_number' => $order->order_number,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'user' => $order->user ? ['name' => $order->user->name] : ['name' => 'Guest'],
                'current_status' => $this->getCurrentOrderStatus($order),
                'pickup_address' => $this->getPickupAddress($order, $dispatcherData),
                'dropoff_address' => $this->getDropoffAddress($order, $dispatcherData),
                'encrypted_order_id' => $this->encryptOrderId($order->id),
                'rider' => $rider,
            ];

            // Get timeline data
            $timelineData = $this->getOrderTimeline($order);

            return view('frontend.order-tracking', compact('orderData', 'timelineData', 'navCategories', 'set_template', 'client_preference_detail', 'client_head'));
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Order tracking error: ' . $e->getMessage(), [
                'order_id' => $order_id ?? 'not provided',
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);
            
            // Return user-friendly error
            // For error case, use primary language
            $langId = ClientLanguage::where('is_primary', 1)->value('language_id') ?? 1;
            $lang_detail = Language::where('id', $langId)->first();
            if ($lang_detail) {
                App::setLocale($lang_detail->sort_code);
            }
            
            $error = [
                'title' => __('System Error'),
                'message' => __('Something went wrong while loading the order tracking page. Please try again later.')
            ];
            
            // Get required variables for layout
            $navCategories = $this->categoryNav($langId);
            $set_template = WebStylingOption::where('is_selected', 1)->first();
            $client_preference_detail = ClientPreference::first();
            $client_head = Client::first();
            
            return view('frontend.order-tracking', compact('error', 'navCategories', 'set_template', 'client_preference_detail', 'client_head'));
        }
    }

    /**
     * Get current order status
     */
    private function getCurrentOrderStatus($order)
    {
        $latestStatus = $order->vendors->flatMap(function ($vendor) {
            return $vendor->allStatus;
        })->sortByDesc('created_at')->first();

        if ($latestStatus && $latestStatus->OrderStatusOption) {
            return __($latestStatus->OrderStatusOption->title);
        }

        return __('Created');
    }

    // Updated getPickupAddress to accept dispatcher data
    private function getPickupAddress($order, $dispatcherData = null)
    {
        if ($dispatcherData && isset($dispatcherData->tasks[0])) {
            return [
                'name' => $dispatcherData->tasks[0]->contact_name ?? '',
                'phone' => $dispatcherData->tasks[0]->contact_number ?? '',
                'address' => trim(($dispatcherData->tasks[0]->flat_no ?? '') . ' ' . ($dispatcherData->tasks[0]->address ?? ''))
            ];
        }
        // fallback
        return [
            'name' => 'John Doe',
            'phone' => '+971123456789',
            'address' => 'No: 10, North street, Khalidiyah, Abu Dhabi, UAE'
        ];
    }

    // Updated getDropoffAddress to accept dispatcher data
    private function getDropoffAddress($order, $dispatcherData = null)
    {
        if ($dispatcherData && isset($dispatcherData->tasks[1])) {
            return [
                'name' => $dispatcherData->tasks[1]->contact_name ?? '',
                'phone' => $dispatcherData->tasks[1]->contact_number ?? '',
                'address' => trim(($dispatcherData->tasks[1]->flat_no ?? '') . ' ' . ($dispatcherData->tasks[1]->address ?? ''))
            ];
        }
        // fallback to order address
        if ($order->address) {
            return [
                'name' => $order->address->name ?? 'Recipient',
                'phone' => $order->address->phone ?? '',
                'address' => $order->address->address ?? ''
            ];
        }
        return [
            'name' => 'Ahmed',
            'phone' => '+971123456780',
            'address' => 'No: 101, south street, Manhal, Abu Dhabi, UAE'
        ];
    }

    /**
     * Get order timeline
     */
    private function getOrderTimeline($order)
    {
        $timeline = [];
        
        // Get all status changes for this order
        $statusHistory = $order->vendors->flatMap(function ($vendor) {
            return $vendor->allStatus;
        })->sortBy('created_at');

        // Get current status
        $currentStatus = $statusHistory->sortByDesc('created_at')->first();
        $currentStatusId = $currentStatus ? $currentStatus->order_status_option_id : null;

        // Get all order status options ordered by sort_order
        $orderStatusesQuery = OrderStatusOption::select('id', 'title', 'image', 'sort_order', 'description')
            ->where('type', 1) // Only order statuses, not dispatch statuses
            ->orderBy('sort_order');

        // If cancelled, only show Placed and Rejected
        if ($currentStatusId == 3) {
            $orderStatuses = $orderStatusesQuery->whereIn('id', [1, 3])->get();
        } else {
            // For all other orders, exclude Rejected
            $orderStatuses = $orderStatusesQuery->where('id', '!=', 3)->get();
        }

        // Icon mapping for different status types
        $iconMapping = [
            'placed' => '/images/status/rider_accepted.png',
            'accepted' => '/images/status/rider_accepted.png',
            'processing' => '/images/status/rider_accepted.png',
            'out for delivery' => '/images/status/out_for_delivery.png',
            'delivered' => '/images/status/request_completed.png',
            'completed' => '/images/status/request_completed.png',
            'cancelled' => '/images/status/rider_reached.png',
            'rejected' => '/images/status/rider_reached.png',
            'picked' => '/images/status/rider_picked.png',
            'arrived' => '/images/status/rider_arrived.png',
            'reached' => '/images/status/rider_reached.png'
        ];

        // Build timeline based on sort_order
        foreach ($orderStatuses as $statusOption) {
            $statusRecord = $statusHistory->where('order_status_option_id', $statusOption->id)->first();
            
            // Determine icon based on status title
            $statusTitle = strtolower($statusOption->title);
            $icon = '/assets/images/order_icon.svg'; // Default icon
            
            foreach ($iconMapping as $keyword => $iconPath) {
                if (strpos($statusTitle, $keyword) !== false) {
                    $icon = $iconPath;
                    break;
                }
            }
            
            // Use custom image if available in database
            if ($statusOption->image) {
                $icon = $statusOption->image;
            }
            
            // Check if this status is completed or current
            $isCompleted = $statusRecord && $statusRecord->created_at;
            $isCurrent = $statusOption->id == $currentStatusId;
            
            // Determine if this status should be shown as completed
            // For accepted orders, show all statuses up to current as completed
            // For rejected orders, only show up to rejection as completed
            $shouldShowAsCompleted = false;
            if ($isCompleted) {
                $shouldShowAsCompleted = true;
            } elseif ($currentStatusId && $currentStatusId != 3) {
                // For non-rejected orders, show statuses before current as completed
                if ($statusOption->id < $currentStatusId) {
                    $shouldShowAsCompleted = true;
                }
            }
            
            // Determine CSS class
            $cssClass = '';
            if ($isCurrent) {
                $cssClass = $currentStatusId == 3 ? 'cancelled' : 'green';
            } elseif ($shouldShowAsCompleted) {
                $cssClass = $statusOption->id == 3 ? 'cancelled' : 'blue';
            }
            
            $timeline[] = [
                'status' => __($statusOption->title),
                'date' => $statusRecord ? $statusRecord->created_at->format('Y-m-d H:i:s') : null,
                'icon' => $icon,
                'class' => $cssClass,
                'is_current' => $isCurrent,
                'is_completed' => $shouldShowAsCompleted,
                'description' => __($statusOption->description ?? '')
            ];
        }

        // If no timeline data, return basic order placed status
        if (empty($timeline)) {
            return [
                [
                    'status' => __('Order placed'),
                    'date' => $order->created_at->format('Y-m-d H:i:s'),
                    'icon' => '/images/status/rider_accepted.png',
                    'class' => 'green',
                    'is_current' => true,
                    'is_completed' => true,
                    'description' => __('Your order has been placed successfully')
                ]
            ];
        }

        return $timeline;
    }
    public function postHomePageData(Request $request)
    {
        $vendor_ids = [];
        $new_products = [];
        $feature_products = [];
        $on_sale_products = [];
        if ($request->has('latitude')) {
            $latitude = $request->latitude;
            Session::put('latitude', $latitude);
        } else {
            $latitude = Session::get('latitude');
        }
        if ($request->has('longitude')) {
            $longitude = $request->longitude;
            Session::put('longitude', $longitude);
        } else {
            $longitude = Session::get('longitude');
        }
        $selectedAddress = ($request->has('selectedAddress')) ? Session::put('selectedAddress', $request->selectedAddress) : Session::get('selectedAddress');
        $selectedPlaceId = ($request->has('selectedPlaceId')) ? Session::put('selectedPlaceId', $request->selectedPlaceId) : Session::get('selectedPlaceId');
        $preferences = Session::get('preferences');
        $currency_id = Session::get('customerCurrency');
        $language_id = Session::get('customerLanguage');
        $brands = Brand::select('id', 'image', 'title')->with(['translation' => function ($q) use ($language_id) {
            $q->where('language_id', $language_id);
        }])->where('status', '!=', $this->field_status)->orderBy('position', 'asc')->get();
        foreach ($brands as $brand) {
            $brand->redirect_url = route('brandDetail', $brand->id);
            $brand->translation_title = $brand->translation->first() ? $brand->translation->first()->title : $brand->title;
        }
        Session::forget('vendorType');
        Session::put('vendorType', $request->type);
        $vendors = Vendor::with('products')->select('id', 'name', 'banner', 'address', 'order_pre_time', 'order_min_amount', 'logo', 'slug', 'latitude', 'longitude')->where($request->type, 1);
        if ($preferences) {
            if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
                $selectedAddress = $preferences->Default_location_name;
                $latitude = $preferences->Default_latitude;
                $longitude = $preferences->Default_longitude;
                Session::put('latitude', $latitude);
                Session::put('longitude', $longitude);
                Session::put('selectedAddress', $selectedAddress);
            } else {
                if (($latitude == $preferences->Default_latitude) && ($longitude == $preferences->Default_longitude)) {
                    Session::put('selectedAddress', $preferences->Default_location_name);
                }
            }
            if (($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {
                $vendors = $vendors->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                    $query->select('vendor_id')
                        ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                });
            }
        }
        $vendors = $vendors->where('status', 1)->inRandomOrder()->get();
        foreach ($vendors as $key => $value) {
            $vendor_ids[] = $value->id;
            $value->vendorRating = $this->vendorRating($value->products);
            // $value->name = Str::limit($value->name, 15, '..');
            if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                $value = $this->getVendorDistanceWithTime($value, $preferences,$latitude, $longitude);
            }
            $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $value->id)->where('status', 1)->get();
            $categoriesList = '';
            foreach ($vendorCategories as $key => $category) {
                if ($category->category) {
                    $categoriesList = $categoriesList . @$category->category->translation_one->name ?? '';
                    if ($key !=  $vendorCategories->count() - 1) {
                        $categoriesList = $categoriesList . ', ';
                    }
                }
            }
            $value->categoriesList = $categoriesList;
        }
        $now = Carbon::now()->toDateTimeString();
        $subscribed_vendors_for_trending = SubscriptionInvoicesVendor::with('features')->whereHas('features', function ($query) {
            $query->where(['subscription_invoice_features_vendor.feature_id' => 1]);
        })
            ->select('id', 'vendor_id', 'subscription_id')
            ->where('end_date', '>=', $now)
            ->whereIn('subscription_invoices_vendor.vendor_id', $vendor_ids)
            ->pluck('vendor_id')->toArray();

        if (($latitude) && ($longitude)) {
            Session::put('vendors', $vendor_ids);
        }

        $trendingVendors = Vendor::whereIn('id', $subscribed_vendors_for_trending)->where('status', 1)->inRandomOrder()->get();

        if ((!empty($trendingVendors) && count($trendingVendors) > 0)) {
            foreach ($trendingVendors as $key => $value) {
                $value->vendorRating = $this->vendorRating($value->products);
                // $value->name = Str::limit($value->name, 15, '..');
                if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                    $value = $this->getVendorDistanceWithTime($value, $preferences,$latitude, $longitude);
                }
                $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $value->id)->where('status', 1)->get();
                $categoriesList = '';
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $categoriesList = $categoriesList . @$category->category->translation_one->name;
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $value->categoriesList = $categoriesList;
            }
        }
        $mostSellingVendors = Vendor::select('vendors.*',DB::raw('count(vendor_id) as max_sales'))->join('order_vendors','vendors.id','=','order_vendors.vendor_id')->whereIn('vendors.id',$vendor_ids)->where('vendors.status', 1)->groupBy('order_vendors.vendor_id')->orderBy(DB::raw('count(vendor_id)'),'desc')->get();
        if ((!empty($mostSellingVendors) && count($mostSellingVendors) > 0)) {
            foreach ($mostSellingVendors as $key => $value) {
                $value->vendorRating = $this->vendorRating($value->products);
                // $value->name = Str::limit($value->name, 15, '..');
                if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                    $value = $this->getVendorDistanceWithTime($value, $preferences,$latitude, $longitude);
                }
                $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $value->id)->where('status', 1)->get();
                $categoriesList = '';
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $categoriesList = $categoriesList . @$category->category->translation_one->name;
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $value->categoriesList = $categoriesList;
            }
        }

        $navCategories = $this->categoryNav($language_id);
        Session::put('navCategories', $navCategories);
        $on_sale_product_details = $this->vendorProducts($vendor_ids, $language_id, $request->type, '', 'USD');
        $new_product_details = $this->vendorProducts($vendor_ids, $language_id, $request->type, 'is_new', $currency_id);
        $feature_product_details = $this->vendorProducts($vendor_ids, $language_id, $request->type, 'is_featured', $currency_id);
        foreach ($new_product_details as  $new_product_detail) {
            $multiply = $new_product_detail->variant->first() ? $new_product_detail->variant->first()->multiplier : 1;
            $title = $new_product_detail->translation->first() ? $new_product_detail->translation->first()->title : $new_product_detail->sku;
            $image_url = $new_product_detail->media->first() ? $new_product_detail->media->first()->image->path['image_fit'] . '600/600' . $new_product_detail->media->first()->image->path['image_path'] : '';
            $new_products[] = array(
                'image_url' => $image_url,
                'sku' => $new_product_detail->sku,
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $new_product_detail->url_slug,
                'averageRating' => number_format($new_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $new_product_detail->inquiry_only,
                'vendor_name' => $new_product_detail->vendor ? $new_product_detail->vendor->name : '',
                'price' => Session::get('currencySymbol') . ' ' . (number_format($new_product_detail->variant->first()->price * $multiply, 2)),
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
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $feature_product_detail->url_slug,
                'averageRating' => number_format($feature_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $feature_product_detail->inquiry_only,
                'vendor_name' => $feature_product_detail->vendor ? $feature_product_detail->vendor->name : '',
                'price' => Session::get('currencySymbol') . ' ' . (number_format($feature_product_detail->variant->first()->price * $multiply, 2)),
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
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $on_sale_product_detail->url_slug,
                'averageRating' => number_format($on_sale_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $on_sale_product_detail->inquiry_only,
                'vendor_name' => $on_sale_product_detail->vendor ? $on_sale_product_detail->vendor->name : '',
                'price' => Session::get('currencySymbol') . ' ' . (number_format($on_sale_product_detail->variant->first()->price * $multiply, 2)),
                'category' => ($on_sale_product_detail->category->categoryDetail->translation->first()) ? $on_sale_product_detail->category->categoryDetail->translation->first()->name : $on_sale_product_detail->category->categoryDetail->slug
            );
        }
        $home_page_labels = HomePageLabel::with('translations')->get();

        $activeOrders = [];

        $user = Auth::user();

        if ($user) {
            $activeOrders = Order::with([
                'vendors' => function ($q) {
                    $q->where('order_status_option_id', '!=', 6);
                },
                'vendors.dineInTable.translations' => function ($qry) use ($language_id) {
                    $qry->where('language_id', $language_id);
                }, 'vendors.dineInTable.category', 'vendors.products', 'vendors.products.media.image', 'vendors.products.pvariant.media.pimage.image', 'user', 'address'
            ])
                ->whereHas('vendors', function ($q) {
                    $q->where('order_status_option_id', '!=', 6);
                })
                ->where('orders.user_id', $user->id)->take(10)
                ->orderBy('orders.id', 'DESC')->get();
            foreach ($activeOrders as $order) {
                foreach ($order->vendors as $vendor) {
                    // dd($vendor->toArray());
                    $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                    $vendor->order_status = $vendor_order_status ? strtolower($vendor_order_status->OrderStatusOption->title) : '';
                    foreach ($vendor->products as $product) {
                        if (isset($product->pvariant) && $product->pvariant->media->isNotEmpty()) {
                            $product->image_url = $product->pvariant->media->first()->pimage->image->path['image_fit'] . '74/100' . $product->pvariant->media->first()->pimage->image->path['image_path'];
                        } elseif ($product->media->isNotEmpty()) {
                            $product->image_url = $product->media->first()->image->path['image_fit'] . '74/100' . $product->media->first()->image->path['image_path'];
                        } else {
                            $product->image_url = ($product->image) ? $product->image['image_fit'] . '74/100' . $product->image['image_path'] : '';
                        }
                        $product->pricedoller_compare = 1;
                    }
                    if ($vendor->delivery_fee > 0) {
                        $order_pre_time = ($vendor->order_pre_time > 0) ? $vendor->order_pre_time : 0;
                        $user_to_vendor_time = ($vendor->user_to_vendor_time > 0) ? $vendor->user_to_vendor_time : 0;
                        $ETA = $order_pre_time + $user_to_vendor_time;
                        $vendor->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $vendor->created_at, $order->scheduled_date_time) : convertDateTimeInTimeZone($vendor->created_at, $user->timezone, 'h:i A');
                    }
                    if ($vendor->dineInTable) {
                        $vendor->dineInTableName = $vendor->dineInTable->translations->first() ? $vendor->dineInTable->translations->first()->name : '';
                        $vendor->dineInTableCapacity = $vendor->dineInTable->seating_number;
                        $vendor->dineInTableCategory = $vendor->dineInTable->category->first() ? $vendor->dineInTable->category->first()->title : '';
                    }
                }
                $order->converted_scheduled_date_time = convertDateTimeInTimeZone($order->scheduled_date_time, $user->timezone, 'M d, Y h:i A');
            }
        }

        $data = [
            'brands' => $brands,
            'vendors' => $vendors,
            'new_products' => $new_products,
            'navCategories' => $navCategories,
            'homePageLabels' => $home_page_labels,
            'feature_products' => $feature_products,
            'on_sale_products' => $on_sale_products,
            'trending_vendors' => (!empty($trendingVendors) && count($trendingVendors) > 0)?$trendingVendors:$mostSellingVendors,
            'active_orders' => $activeOrders
        ];
        return $this->successResponse($data);
    }

    public function vendorProducts($venderIds, $langId, $type, $where = '', $currency = 'USD')
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
                    $value->variant[$k]->multiplier = Session::get('currencyMultiplier');
                }
            }
        }
        return $products;
    }

    public function changePrimaryData(Request $request)
    {
        if ($request->has('type') && $request->type == 'language') {
            $clientLanguage = ClientLanguage::where('language_id', $request->value1)->first();
            if ($clientLanguage) {
                $lang_detail = Language::where('id', $request->value1)->first();
                App::setLocale($lang_detail->sort_code);
                session()->put('locale', $lang_detail->sort_code);
                Session::put('customerLanguage', $request->value1);
            }
        }
        if ($request->has('type') && $request->type == 'currency') {
            $clientCurrency = ClientCurrency::where('currency_id', $request->value1)->first();
            if ($clientCurrency) {
                $currency_detail = Currency::where('id', $request->value1)->first();
                if($currency_detail->iso_code == "AED"){
                    Session::put('currencySymbol', $currency_detail->iso_code);
                }else{
                    Session::put('currencySymbol', $request->value2);
                }


                Session::put('customerCurrency', $request->value1);
                Session::put('iso_code', $currency_detail->iso_code);
                Session::put('currencyMultiplier', $clientCurrency->doller_compare);
            }
        }

        $data['customerLanguage'] = Session::get('customerLanguage');
        $data['customerCurrency'] = Session::get('customerCurrency');
        $data['currencySymbol'] = Session::get('currencySymbol');
        return response()->json(['status' => 'success', 'message' => 'Saved Successfully!', 'data' => $data]);
    }

    public function changePaginate(Request $request)
    {
        $perPage = 12;
        if ($request->has('itemPerPage')) {
            $perPage = $request->itemPerPage;
        }
        Session::put('cus_paginate', $perPage);
        return response()->json(['status' => 'success', 'message' => 'Saved Successfully!', 'data' => $perPage]);
    }

    public function getClientPreferences(Request $request)
    {
        $clientPreferences = ClientPreference::first();
        if ($clientPreferences) {
            $dinein_check = $clientPreferences->dinein_check;
            $delivery_check = $clientPreferences->delivery_check;
            $takeaway_check = $clientPreferences->takeaway_check;
            $age_restriction = $clientPreferences->age_restriction;
            return response()->json(["age_restriction" => $age_restriction, "dinein_check" => $dinein_check, "delivery_check" => $delivery_check, "takeaway_check" => $takeaway_check]);
        }
    }


    /////    new home page
    public function indexTemplateOne(Request $request)
    {
        try {
            $home = array();
            $vendor_ids = array();
            if ($request->has('ref')) {
                session(['referrer' => $request->query('ref')]);
            }
            $latitude = Session::get('latitude');
            $longitude = Session::get('longitude');
            $curId = Session::get('customerCurrency');
            $preferences = Session::get('preferences');
            $langId = Session::get('customerLanguage');
            $client_config = Session::get('client_config');
            $selectedAddress = Session::get('selectedAddress');
            $navCategories = $this->categoryNav($langId);
            Session::put('navCategories', $navCategories);
            $clientPreferences = ClientPreference::first();
            $count = 0;
            if ($clientPreferences) {
                if ($clientPreferences->dinein_check == 1) {
                    $count++;
                }
                if ($clientPreferences->takeaway_check == 1) {
                    $count++;
                }
                if ($clientPreferences->delivery_check == 1) {
                    $count++;
                }
            }
            if ($preferences) {
                if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
                    $selectedAddress = $preferences->Default_location_name;
                    $latitude = $preferences->Default_latitude;
                    $longitude = $preferences->Default_longitude;
                    Session::put('latitude', $latitude);
                    Session::put('longitude', $longitude);
                    Session::put('selectedAddress', $selectedAddress);
                }
            }
            $banners = Banner::where('status', 1)->where('validity_on', 1)
                ->where(function ($q) {
                    $q->whereNull('start_date_time')->orWhere(function ($q2) {
                        $q2->whereDate('start_date_time', '<=', Carbon::now())
                            ->whereDate('end_date_time', '>=', Carbon::now());
                    });
                })->orderBy('sorting', 'asc')->with('category')->with('vendor')->get();
            $home_page_labels = HomePageLabel::with('translations')->where('is_active', 1)->orderBy('order_by')->get();

            $only_cab_booking = OnboardSetting::where('key_value', 'home_page_cab_booking')->count();
            if ($only_cab_booking == 1)
                return Redirect::route('categoryDetail', 'cabservice');
            $home_page_pickup_labels = CabBookingLayout::with(['translations' => function ($q) use ($langId) {
                $q->where('language_id', $langId);
            }])->where('is_active', 1)->orderBy('order_by')->get();

            dd($home_page_labels);
            return view('frontend.home-template-one')->with(['home' => $home, 'count' => $count, 'homePagePickupLabels' => $home_page_pickup_labels, 'homePageLabels' => $home_page_labels, 'clientPreferences' => $clientPreferences, 'banners' => $banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
        } catch (Exception $e) {
            pr($e->getCode());
            die;
        }
    }
    public function redirectStore(Request $request)
    {
        // Check the user agent to detect the device
        $userAgent = $request->header('User-Agent');
        $client = ClientPreference::first();
        if (strpos($userAgent, 'iPhone') || strpos($userAgent, 'iPad')) {
            // Redirect to App Store for iOS devices
            return redirect()->away($client->ios_link);
        } elseif (strpos($userAgent, 'Android')) {
            // Redirect to Play Store for Android devices
            return redirect()->away($client->android_app_link);
        } else {
            // Redirect to a generic page if device cannot be detected
            return redirect()->away($client->android_app_link);
        }
    }
}
