<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, ClientPreference, MapProvider, SmsProvider, Template, Currency, Language, ClientLanguage, ClientCurrency, Nomenclature, ReferAndEarn, SocialMedia, VendorRegistrationDocument, PageTranslation, BrandTranslation, VariantTranslation, ProductTranslation, Category_translation, AddonOptionTranslation, DriverRegistrationDocument, VariantOptionTranslation, OrderRejectingReason, OrderRejectingReasonTranslation, OrderVendor};
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Support\Facades\DB;

class ClientPreferenceController extends BaseController
{
    public function index()
    {
        $client = Auth::user();
        $mapTypes = MapProvider::where('status', '1')->get();
        $smsTypes = SmsProvider::where('status', '1')->get();
        $ClientPreference = ClientPreference::where('client_code', $client->code)->first();
        $preference = $ClientPreference ? $ClientPreference : new ClientPreference();
        $client_languages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
            ->where('client_languages.client_code', Auth::user()->code)
            ->where('client_languages.is_active', 1)
            ->orderBy('client_languages.is_primary', 'desc')->get();
        $file_types = ['image/*' => 'Image', '.pdf' => 'Pdf'];
        $file_types_driver = ['image/*' => 'Image', '.pdf' => 'Pdf', '.txt' => 'Text'];
        $vendor_registration_documents = VendorRegistrationDocument::with('primary')->get();
        $driver_registration_documents = DriverRegistrationDocument::with('primary')->get();
        if ($preference->reffered_by_amount == null) {
            $reffer_by = 0;
        } else {
            $reffer_by = $preference->reffered_by_amount;
        }
        if ($preference->reffered_to_amount == null) {
            $reffer_to = 0;
        } else {
            $reffer_to = $preference->reffered_to_amount;
        }

        $last_mile_teams = [];
        # if last mile on
        if (isset($preference) && $preference->need_delivery_service == '1') {
            $last_mile_teams = $this->getLastMileTeams();
        }
        $rejecting_reasons = OrderRejectingReason::with('primary')->where('type', 1)->get(['id', 'name', 'status']);
        return view('backend/setting/config')->with(['last_mile_teams' => $last_mile_teams, 'client' => $client, 'preference' => $preference, 'mapTypes' => $mapTypes, 'smsTypes' => $smsTypes, 'client_languages' => $client_languages, 'file_types' => $file_types, 'vendor_registration_documents' => $vendor_registration_documents, 'driver_registration_documents' => $driver_registration_documents, 'reffer_by' => $reffer_by, 'reffer_to' => $reffer_to, 'file_types_driver' => $file_types_driver, 'rejecting_reasons' => $rejecting_reasons]);
    }

    public function getCustomizePage(ClientPreference $clientPreference)
    {
        $curArray = [];
        $cli_langs = [];
        $reffer_by = "";
        $reffer_to = "";
        $cli_currs = [];
        $client = Auth::user();
        $social_media_details = SocialMedia::get();
        $webTemplates = Template::where('for', '1')->get();
        $appTemplates = Template::where('for', '2')->get();
        $languages = Language::where('id', '>', '0')->get();
        $currencies = Currency::where('id', '>', '0')->get();
        $curtableData = array_chunk($currencies->toArray(), 2);
        $primaryCurrency = ClientCurrency::where('is_primary', 1)->first();
        $ClientPreference = ClientPreference::with('language', 'primarylang', 'domain', 'currency.currency', 'primary.currency')->select('client_code', 'theme_admin', 'distance_unit', 'date_format', 'time_format', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'verify_email', 'verify_phone', 'web_template_id', 'app_template_id', 'primary_color', 'secondary_color', 'reffered_by_amount', 'reffered_to_amount')->where('client_code', $client->code)->first();
        $preference = $ClientPreference ? $ClientPreference : new ClientPreference();
        $nomenclature_value = Nomenclature::first();
        foreach ($preference->currency as $value) {
            $cli_currs[] = $value->currency_id;
        }
        foreach ($preference->language as $value) {
            $cli_langs[] = $value->language_id;
        }
        $client_languages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
            ->where('client_languages.client_code', Auth::user()->code)
            ->where('client_languages.is_active', 1)
            ->orderBy('client_languages.is_primary', 'desc')->get();
        return view('backend.setting.customize', compact('client', 'nomenclature_value', 'cli_langs', 'languages', 'currencies', 'preference', 'cli_currs', 'curtableData', 'webTemplates', 'appTemplates', 'primaryCurrency', 'social_media_details', 'client_languages'));
    }

    public function referandearnUpdate(Request $request, $code)
    {
        $cp = new ClientPreference();
        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();
        if ($preference) {
            $preference->reffered_to_amount = $request->reffered_to_amount;
            $preference->reffered_by_amount = $request->reffered_by_amount;
            $preference->save();
            return redirect()->route('configure.index')->with('success', 'Client configurations updated successfully!');
        }
    }

    public function updateMaxReturnDays(Request $request)
    {
        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();
        if ($preference) {
            $preference->max_return_days = $request->max_return_days;
            $preference->save();
            return redirect()->route('configure.index')->with('success', 'Max Return Days updated successfully!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClientPreference  $clientPreference
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {


        $cp = new ClientPreference();
        $preference = ClientPreference::where('client_code', Auth::user()->code)->first();
        if (!$preference) {
            $preference = new ClientPreference();
            $preference->client_code = $code;
        }
        $keyShouldNot = array('dispacher_home_other_service_key_url', 'dispacher_home_other_service_key_code', 'dispacher_home_other_service_key', 'pickup_delivery_service_key_url', 'pickup_delivery_service_key_code', 'pickup_delivery_service_key', 'delivery_service_key_url', 'delivery_service_key_code', 'delivery_service_key', 'need_delivery_service', 'need_dispacher_home_other_service', 'need_dispacher_ride', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'is_hyperlocal', '_token', 'social_login', 'send_to', 'languages', 'hyperlocals', 'currency_data', 'multiply_by', 'cuid', 'primary_language', 'primary_currency', 'currency_data', 'verify_config', 'custom_mods_config', 'distance_to_time_calc_config');

        foreach ($request->all() as $key => $value) {
            if (!in_array($key, $keyShouldNot)) {
                $preference->{$key} = $value;
            }
        }

        /* Hyperlocal update */
        if ($request->has('hyperlocals') && $request->hyperlocals == '1') {
            $preference->is_hyperlocal = ($request->has('is_hyperlocal') && $request->is_hyperlocal == 'on') ? 1 : 0;


            if ($request->has('is_hyperlocal') && $request->is_hyperlocal == 'on') {
                if ((!$request->has('Default_location_name')) || ($request->Default_location_name == '')
                    || (!$request->has('Default_latitude')) || ($request->Default_latitude == '')
                    || (!$request->has('Default_longitude')) || ($request->Default_longitude == '')
                ) {
                    return redirect()->route('configure.index')->with('error', 'Invalid Hyperlocal Data');
                }
                $preference->Default_location_name = $request->Default_location_name;
                $preference->Default_latitude = $request->Default_latitude;
                $preference->Default_longitude = $request->Default_longitude;
            }
        }



        /* social login update */
        if ($request->has('social_login') && $request->social_login == '1') {
            $preference->fb_login = ($request->has('fb_login') && $request->fb_login == 'on') ? 1 : 0;
            $preference->twitter_login = ($request->has('twitter_login') && $request->twitter_login == 'on') ? 1 : 0;
            $preference->google_login = ($request->has('google_login') && $request->google_login == 'on') ? 1 : 0;
            $preference->apple_login = ($request->has('apple_login') && $request->apple_login == 'on') ? 1 : 0;
        }
        if ($request->has('verify_config') && $request->verify_config == '1') {
            $preference->verify_email = ($request->has('verify_email') && $request->verify_email == 'on') ? 1 : 0;
            $preference->verify_phone = ($request->has('verify_phone') && $request->verify_phone == 'on') ? 1 : 0;
            if ((!$request->has('dinein_check') && !$request->dinein_check == 'on')
                && (!$request->has('takeaway_check') && !$request->dinein_check == 'on')
                && (!$request->has('delivery_check') && !$request->dinein_check == 'on')
            ) {
                return redirect()->route('configure.index')->with('error', 'One Option must be acitve');
            }
            $preference->dinein_check = ($request->has('dinein_check') && $request->dinein_check == 'on') ? 1 : 0;
            $preference->takeaway_check = ($request->has('takeaway_check') && $request->takeaway_check == 'on') ? 1 : 0;
            $preference->delivery_check = ($request->has('delivery_check') && $request->delivery_check == 'on') ? 1 : 0;
        }
        if ($request->has('custom_mods_config') && $request->custom_mods_config == '1') {
            $preference->enquire_mode = ($request->has('enquire_mode') && $request->enquire_mode == 'on') ? 1 : 0;
            $preference->pharmacy_check = ($request->has('pharmacy_check') && $request->pharmacy_check == 'on') ? 1 : 0;
            $preference->celebrity_check = ($request->has('celebrity_check') && $request->celebrity_check == 'on') ? 1 : 0;
            $preference->subscription_mode = ($request->has('subscription_mode') && $request->subscription_mode == 'on') ? 1 : 0;
            $preference->tip_before_order = ($request->has('tip_before_order') && $request->tip_before_order == 'on') ? 1 : 0;
            $preference->tip_after_order = ($request->has('tip_after_order') && $request->tip_after_order == 'on') ? 1 : 0;
            $preference->off_scheduling_at_cart = ($request->has('off_scheduling_at_cart') && $request->off_scheduling_at_cart == 'on') ? 1 : 0;
            $preference->isolate_single_vendor_order = ($request->has('isolate_single_vendor_order') && $request->isolate_single_vendor_order == 'on') ? 1 : 0;
            $preference->android_maintenance_mode = ($request->has('android_maintenance_mode') && $request->android_maintenance_mode == 'on') ? 1 : 0;
            $preference->ios_maintenance_mode = ($request->has('ios_maintenance_mode') && $request->ios_maintenance_mode == 'on') ? 1 : 0;
            $preference->android_app_force_update_status = ($request->has('android_app_force_update_status') && $request->android_app_force_update_status == 'on') ? 1 : 0;
            $preference->ios_app_force_update_status = ($request->has('ios_app_force_update_status') && $request->ios_app_force_update_status == 'on') ? 1 : 0;
        }
        if ($request->has('distance_to_time_calc_config') && $request->distance_to_time_calc_config == '1') {
            $preference->distance_unit_for_time = (($request->has('distance_unit_for_time')) && ($request->distance_unit_for_time != '')) ? $request->distance_unit_for_time : 'kilometer';
            $preference->distance_to_time_multiplier = (($request->has('distance_to_time_multiplier')) && ($request->distance_to_time_multiplier != '')) ? $request->distance_to_time_multiplier : 2;
        }
        if ($request->has('primary_language')) {
            $deactivate_language = ClientLanguage::where('client_code', Auth::user()->code)->where('is_primary', 1)->first();
            if ($deactivate_language) {
                $deactivate_language->is_active = '0';
                $deactivate_language->is_primary = '0';
                $deactivate_language->save();
            }
            $primary_change = ClientLanguage::where('client_code', Auth::user()->code)->where('language_id', $request->primary_language)->update(['is_active' => 1, 'is_primary' => 1]);
            if (!$primary_change) {
                ClientLanguage::insert([
                    'is_active' => 1,
                    'is_primary' => 1,
                    'client_code' => Auth::user()->code,
                    'language_id' => $request->primary_language,
                ]);
            }
            PageTranslation::where('language_id', $deactivate_language->language_id)->update(['language_id' => $request->primary_language]);
            BrandTranslation::where('language_id', $deactivate_language->language_id)->update(['language_id' => $request->primary_language]);
            VariantTranslation::where('language_id', $deactivate_language->language_id)->update(['language_id' => $request->primary_language]);
            ProductTranslation::where('language_id', $deactivate_language->language_id)->update(['language_id' => $request->primary_language]);
            Category_translation::where('language_id', $deactivate_language->language_id)->update(['language_id' => $request->primary_language]);
            AddonOptionTranslation::where('language_id', $deactivate_language->language_id)->update(['language_id' => $request->primary_language]);
            VariantOptionTranslation::where('language_id', $deactivate_language->language_id)->update(['language_id' => $request->primary_language]);
            $exist_language_id = array();
            if ($request->has('languages')) {
                foreach ($request->languages as $lan) {
                    if ($lan != $request->primary_language) {
                        $client_language = ClientLanguage::where('client_code', Auth::user()->code)->where('language_id', $lan)->first();
                        if (!$client_language) {
                            $client_language = new ClientLanguage();
                            $client_language->client_code = Auth::user()->code;
                        }
                        $client_language->is_primary = 0;
                        $client_language->language_id = $lan;
                        $client_language->is_active = 1;
                        $client_language->save();
                        $exist_language_id[] = $client_language->language_id;
                    }
                }
            }
            $deactivateLanguages = ClientLanguage::where('client_code', Auth::user()->code)->whereNotIn('language_id', $exist_language_id)->where('is_primary', 0)->update(['is_active' => 0]);
        }

        if ($request->has('primary_currency')) {
            $oldAdditional = ClientCurrency::where('currency_id', $request->primary_currency)
                ->where('is_primary', 0)->delete();
            $primaryCur = ClientCurrency::where('is_primary', 1)->update(['currency_id' => $request->primary_currency, 'doller_compare' => 1]);
        }
        if ($request->has('primary_currency') && !$request->has('currency_data')) {
            $delete = ClientCurrency::where('client_code', Auth::user()->code)->where('is_primary', 0)->delete();
        }
        if ($request->has('currency_data') && $request->has('multiply_by')) {
            $cur_multi = $exist_cid = array();
            foreach ($request->currency_data as $key => $value) {
                $exist_cid[] = $value;
                $curr = ClientCurrency::where('currency_id', $value)->where('client_code', Auth::user()->code)->first();
                $multiplier = array_key_exists($key, $request->multiply_by) ? $request->multiply_by[$key] : 1;
                if (!$curr) {
                    $cur_multi[] = [
                        'currency_id' => $value,
                        'client_code' => Auth::user()->code,
                        'is_primary' => 0,
                        'doller_compare' => $multiplier
                    ];
                } else {
                    ClientCurrency::where('currency_id', $value)->where('client_code', Auth::user()->code)
                        ->update(['doller_compare' => $multiplier]);
                }
            }
            ClientCurrency::insert($cur_multi);
            $delete = ClientCurrency::where('client_code', Auth::user()->code)->where('is_primary', 0)
                ->whereNotIn('currency_id', $exist_cid)->delete();
        }



        $preference->save();


        $preferenceset = ClientPreference::where('client_code', Auth::user()->code)->first();
        if (isset($request->need_delivery_service) && !empty($request->need_delivery_service)) {
            try {
                $client = new GClient(['headers' => ['personaltoken' => $request->delivery_service_key, 'shortcode' => $request->delivery_service_key_code, 'content-type' => 'application/json']]);
                $url = $request->delivery_service_key_url;
                $res = $client->post($url . '/api/check-dispatcher-keys');
                $response = json_decode($res->getBody(), true);
                if ($response && $response['status'] == 400) {
                    return redirect()->route('configure.index')->with('error', 'Last Mile Delivery Keys incorrect !');
                }
            } catch (\Exception $e) {
                return redirect()->route('configure.index')->with('error', 'Invalid Last Mile Delivery Dispatcher URL !');
            }
            $preferenceset->need_delivery_service = ($request->has('need_delivery_service') && $request->need_delivery_service == 'on') ? 1 : 0;
            $preferenceset->delivery_service_key_url = $request->delivery_service_key_url;
            $preferenceset->delivery_service_key_code = $request->delivery_service_key_code;
            $preferenceset->delivery_service_key = $request->delivery_service_key;
        } else {
            $preferenceset->need_delivery_service = $preferenceset->need_delivery_service;
        }

        if (isset($request->need_dispacher_ride) && !empty($request->need_dispacher_ride)) {
            try {
                $client = new GClient(['headers' => ['personaltoken' => $request->pickup_delivery_service_key, 'shortcode' => $request->pickup_delivery_service_key_code, 'content-type' => 'application/json']]);
                $url = $request->pickup_delivery_service_key_url;
                $res = $client->post($url . '/api/check-dispatcher-keys');
                $response = json_decode($res->getBody(), true);
                if ($response && $response['status'] == 400) {
                    return redirect()->route('configure.index')->with('error', 'Pickup & Delivery Keys incorrect !');
                }
            } catch (\Exception $e) {
                return redirect()->route('configure.index')->with('error', 'Invalid Pickup & Delivery Dispatcher URL !');
            }
            $preferenceset->need_dispacher_ride = ($request->has('need_dispacher_ride') && $request->need_dispacher_ride == 'on') ? 1 : 0;
            $preferenceset->pickup_delivery_service_key_url = $request->pickup_delivery_service_key_url;
            $preferenceset->pickup_delivery_service_key_code = $request->pickup_delivery_service_key_code;
            $preferenceset->pickup_delivery_service_key = $request->pickup_delivery_service_key;
        } else {
            $preferenceset->need_dispacher_ride = $preferenceset->need_dispacher_ride;
        }

        if (isset($request->need_dispacher_home_other_service) && !empty($request->need_dispacher_home_other_service)) {
            try {
                $client = new GClient([
                    'headers' => [
                        'personaltoken' => $request->dispacher_home_other_service_key,
                        'shortcode' => $request->dispacher_home_other_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $request->dispacher_home_other_service_key_url;
                $res = $client->post($url . '/api/check-dispatcher-keys');
                $response = json_decode($res->getBody(), true);
                if ($response && $response['status'] == 400) {
                    return redirect()->route('configure.index')->with('error', 'On Demand Services Keys incorrect !');
                }
            } catch (\Exception $e) {
                return redirect()->route('configure.index')->with('error', 'Invalid On Demand Services Dispatcher URL !');
            }
            $preferenceset->need_dispacher_home_other_service = ($request->has('need_dispacher_home_other_service') && $request->need_dispacher_home_other_service == 'on') ? 1 : 0;
            $preferenceset->dispacher_home_other_service_key_url = $request->dispacher_home_other_service_key_url;
            $preferenceset->dispacher_home_other_service_key_code = $request->dispacher_home_other_service_key_code;
            $preferenceset->dispacher_home_other_service_key = $request->dispacher_home_other_service_key;
        } else {
            $preferenceset->need_dispacher_home_other_service = $preferenceset->need_dispacher_home_other_service;
        }
        $preferenceset->save();


        if ($request->has('send_to') && $request->send_to == 'customize') {
            return redirect()->route('configure.customize')->with('success', 'Client customizations updated successfully!');
        }
        return redirect()->route('configure.index')->with('success', 'Client configurations updated successfully!');
    }




    # get last mile teams 
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
    # check if last mile delivery on 
    public function checkIfLastMileOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }


    public function postUpdateDomain(Request $request, $id)
    {
        $rules = array('custom_domain' => 'required|max:150');
        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $client = Client::where('code', Auth::user()->code)->first();
        // $client->custom_domain = $request->custom_domain;
        // $client->save();
        $id = Auth::user()->code;
        # if submit custom domain by client
        if ($request->custom_domain && $request->custom_domain != $client->custom_domain) {
            try {
                $my_url =   $request->custom_domain;

                $data1 = [
                    'domain' => $my_url
                ];

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "localhost:3000/add_subdomain",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($data1),
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/json",
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                $res = json_decode($response);
                if (isset($res->error) && $res->error->statusCode == 400) {
                    $error = isset($res->error->customMessage) ? $res->error->customMessage : 'ERROR';
                    return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => $error]));
                }

                $exists = Client::on('god')->where('code', $id)->where('custom_domain', $request->custom_domain)->count();
                if ($exists) {
                    return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => 'Domain name "' . $request->custom_domain . '" is not available. Please select a different domain']));
                } else {
                    Client::on('god')->where('code', $id)->update(['custom_domain' => $request->custom_domain]);
                    $dbname = DB::connection()->getDatabaseName();
                    if ($dbname != env('DB_DATABASE')) {
                        Client::where('id', '!=', 0)->update(['custom_domain' => $request->custom_domain]);
                    }
                }
                return redirect()->route('configure.customize')->with('success', 'Client customize data updated successfully!');
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => $e->getMessage()]));
            }
        } else {
            return redirect()->back()->withInput()->withErrors(new \Illuminate\Support\MessageBag(['custom_domain' => 'Domain name "' . $request->custom_domain . '" is already pointed. Please select a different domain']));
        }
    }
    public function addEditCancelReason(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'language_id' => 'required|exists:languages,id',
            'status' => 'required|in:1,2',
        ]);

        $languageId = (int) $request->language_id;
        $clientLanguage = ClientLanguage::where('client_code', Auth::user()->code)
            ->where('language_id', $languageId)
            ->where('is_active', 1)
            ->first();

        if (!$clientLanguage) {
            return response()->json([
                'status' => false,
                'message' => 'Selected language is not enabled for this client.',
            ]);
        }

        $duplicateTranslationQuery = OrderRejectingReasonTranslation::where('language_id', $languageId)
            ->where('reason', $request->reason);

        if ($request->id) {
            $duplicateTranslationQuery->where('order_rejecting_reason_id', '!=', $request->id);
        }

        if ($duplicateTranslationQuery->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Cancellation reason already exists for the selected language.',
            ]);
        }

        if ($clientLanguage->is_primary == 1) {
            $existingReason = OrderRejectingReason::where(['name' => $request->reason, 'type' => 1]);
            if ($request->id) {
                $existingReason->where('id', '!=', $request->id);
            }

            if ($existingReason->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cancellation reason already exists.',
                ]);
            }
        }

        DB::beginTransaction();

        try {
            $cancellationReason = OrderRejectingReason::find($request->id);

            if (!$cancellationReason) {
                $cancellationReason = new OrderRejectingReason();
                $cancellationReason->type = 1;
            }

            if ($clientLanguage->is_primary == 1 || !$cancellationReason->exists) {
                $cancellationReason->name = $request->reason;
            }

            $cancellationReason->status = $request->status;
            $cancellationReason->save();

            OrderRejectingReasonTranslation::updateOrCreate(
                [
                    'order_rejecting_reason_id' => $cancellationReason->id,
                    'language_id' => $languageId,
                ],
                [
                    'reason' => $request->reason,
                ]
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => $request->id ? 'Cancellation reason updated successfully.' : 'Cancellation reason added successfully.',
                'data' => $cancellationReason
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function deleteCancelReason(Request $request)
    {
        if (isset($request->id) && !empty(isset($request->id))) {
            $isUsed = OrderVendor::where('reject_reason', $request->id)->exists();

            if ($isUsed) {
                return response()->json([
                    'status' => false,
                    'message' => 'This cancellation reason is in use and cannot be deleted.'
                ]);
            }

            // Proceed to delete the cancellation reason
            OrderRejectingReason::where('id', $request->id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Cancellation reason deleted successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong,Please try gain later !.'
            ]);
        }
    }

    public function editCancelReason($domain, $id)
    {
        $cancelReason = null;
        if ($id > 0) {
            $cancelReason = OrderRejectingReason::with('primary')->where('id', $id)->where('type', 1)->first();
            
            if (!$cancelReason) {
                return response()->json(array('success' => false, 'message' => 'Cancellation reason not found'));
            }
        } else {
            // For create mode, create empty object
            $cancelReason = new OrderRejectingReason();
        }
        
        $client_languages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.is_primary')
            ->where('client_languages.client_code', Auth::user()->code)
            ->where('client_languages.is_active', 1)
            ->orderBy('client_languages.is_primary', 'desc')->get();
        
        $returnHTML = view('backend.setting.cancelReasonForm')->with(['cancelReason' => $cancelReason, 'client_languages' => $client_languages])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getCancelReason(Request $request, $domain, $id)
    {
        $languageId = $request->get('language_id');

        $cancelReason = OrderRejectingReason::with([
            'translation' => function ($query) use ($languageId) {
                if ($languageId) {
                    $query->where('language_id', $languageId);
                }
            },
            'primary'
        ])->find($id);

        if (!$cancelReason) {
            return response()->json([
                'success' => false,
                'message' => 'Cancellation reason not found.'
            ], 404);
        }

        $reasonText = '';

        if ($languageId) {
            $clientLanguage = ClientLanguage::where('client_code', Auth::user()->code)
                ->where('language_id', $languageId)
                ->where('is_active', 1)
                ->first();

            if ($cancelReason->translation) {
                $reasonText = $cancelReason->translation->reason;
            } elseif ($clientLanguage && $clientLanguage->is_primary) {
                $reasonText = $cancelReason->name;
            }
        } else {
            $reasonText = optional($cancelReason->primary)->reason ?? $cancelReason->name;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $cancelReason->id,
                'status' => $cancelReason->status,
                'reason' => $reasonText,
                'translation' => $cancelReason->translation ? [
                    'reason' => $cancelReason->translation->reason,
                ] : null,
            ]
        ]);
    }
}
