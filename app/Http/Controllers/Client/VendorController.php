<?php

namespace App\Http\Controllers\Client;

use Image;
use Phumbor;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\UserVendor;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\VendorImport;
use App\Http\Traits\ApiResponser;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ToasterResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\ClientCurrency;
use App\Models\{PaymentOption, CsvProductImport, Vendor, CsvVendorImport, VendorSlot, VendorDineinCategory, VendorBlockDate, Category, ServiceArea, ClientLanguage, AddonSet, Client, ClientPreference, EmailTemplate, Product, Type, VendorCategory, UserPermissions, VendorDocs, SubscriptionPlansVendor, SubscriptionInvoicesVendor, SubscriptionInvoiceFeaturesVendor, SubscriptionFeaturesListVendor, VendorDineinTable, Woocommerce, ItemCategory};
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\File;

class VendorController extends BaseController
{
    use ToasterResponser;
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFilterData(Request $request)
    {
        $dinein_check = '';
        $takeaway_check = '';
        $delivery_check = '';
        $client_preference = ClientPreference::first();
        if ($client_preference) {
            $dinein_check = $client_preference->dinein_check;
            $takeaway_check = $client_preference->takeaway_check;
            $delivery_check = $client_preference->delivery_check;
        }
        $vendors = Vendor::withCount(['products', 'orders', 'currentlyWorkingOrders'])->with('slot')->where('status', $request->status)->orderBy('id', 'desc');
        if (Auth::user()->is_superadmin == 0) {
            $vendors = $vendors->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendors = $vendors->get();
        foreach ($vendors as $vendor) {
            $offers = [];
            $vendor->show_url = route('vendor.catalogs', $vendor->id);
            $vendor->destroy_url = route('vendor.destroy', $vendor->id);
            $vendor->add_category_option = ($vendor->add_category == 0) ? 'No' : 'Yes';
            if ($vendor->show_slot == 1) {
                $vendor->show_slot_option = "Open";
                $vendor->show_slot_label = "success";
            } elseif ($vendor->slot->count() > 0) {
                $vendor->show_slot_option = "Open";
                $vendor->show_slot_label = "success";
            } else {
                $vendor->show_slot_label = "danger";
                $vendor->show_slot_option = "Closed";
            }
            $offers[] = $dinein_check == 1 && $vendor->dine_in == 1 ? 'Dine In' : '';
            $offers[] = $takeaway_check == 1 && $vendor->takeaway == 1 ? 'Take Away' : '';
            $offers[] = $delivery_check == 1 && $vendor->delivery == 1 ? 'Delivery' : '';
            $vendor->offers = $offers;
        }
        return Datatables::of($vendors)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))) {
                            return true;
                        }
                        return false;
                    });
                }
            })->make(true);
    }
    public function index()
    {
        $user = Auth::user();
        $csvVendors = CsvVendorImport::all();
        $vendor_docs = collect(new VendorDocs);
        $client_preferences = ClientPreference::first();
        $vendors = Vendor::withCount(['products', 'orders', 'currentlyWorkingOrders'])->with('slot')->orderBy('id', 'desc');
        if ($user->is_superadmin == 0) {
            $vendors = $vendors->whereHas('permissionToUser', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }
        $only_active_vendors = $vendors;
        $vendors = $vendors->get();
        $only_active_vendors = $only_active_vendors->where('status', 1)->get();
        $active_vendor_count = $vendors->where('status', 1)->count();
        $blocked_vendor_count = $vendors->where('status', 2)->count();
        $awaiting__Approval_vendor_count = $vendors->where('status', 0)->count();
        $available_vendors_count = 0;
        $vendors_product_count = 0;
        $vendors_active_order_count = 0;
        foreach ($only_active_vendors as $key => $vendor) {
            $vendors_product_count += $vendor->products->count();
            $vendors_active_order_count += $vendor->currentlyWorkingOrders->count();
            if ($vendor->show_slot == 1) {
                $available_vendors_count += 1;
            } elseif ($vendor->slot->count() > 0) {
                $available_vendors_count += 1;
            }
        }
        $total_vendor_count = $vendors->count();
        if (count($vendors) == 1 && $user->is_superadmin == 0) {
            return Redirect::route('vendor.catalogs', $vendors->first()->id);
        } else {
            return view('backend/vendor/index')->with([
                'vendors' => $vendors,
                'vendor_docs' => $vendor_docs,
                'csvVendors' => $csvVendors,
                'total_vendor_count' => $total_vendor_count,
                'client_preferences' => $client_preferences,
                'active_vendor_count' => $active_vendor_count,
                'blocked_vendor_count' => $blocked_vendor_count,
                'available_vendors_count' => $available_vendors_count,
                'awaiting__Approval_vendor_count' => $awaiting__Approval_vendor_count,
                'vendors_product_count' => $vendors_product_count,
                'vendors_active_order_count' => $vendors_active_order_count
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:150|unique:vendors',
            'email'         => 'required|email|max:50||unique:users',
            //'password'      => 'required|string|min:6|max:50',
            'phone_number'  => 'required|regex:/^5[0-9]{8}$/|unique:users',
            'address' => 'required|string',
        );
        Validator::make($request->all(), $rules)->validate();

        DB::beginTransaction();
        try {
            $vendor = new Vendor();
            $saveVendor = $this->save($request, $vendor, 'false');
            if ($saveVendor > 0) {
                $dispatch_domain = $this->checkIfPickupDeliveryOnCommon();
                if ($dispatch_domain && $dispatch_domain != false) {
                    $dispatch_domain['vendor_id'] = $saveVendor;
                    $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
                    $dispatch_domain['token'] = $token;
                    $data = [];
                    $request_from_dispatch = $this->checkUpdateVendorToDispatch($dispatch_domain);
                    if ($request_from_dispatch && isset($request_from_dispatch['status']) && $request_from_dispatch['status'] == 200) {
                        DB::commit();
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Vendor created Successfully!',
                            'data' => $saveVendor
                        ]);
                    } else {
                        DB::rollback();
                        return $request_from_dispatch;
                    }
                } else {
                    // Pickup & Delivery service is not available, but don't rollback - just commit the vendor creation
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Vendor created Successfully!',
                        'data' => $saveVendor
                    ]);
                }
            } else {
                DB::rollback();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error while creating user or vendor.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, Vendor $vendor, $update = 'false')
    {
        if ($update == 'false') {
            $user = new User();
        } else {
            $user = User::where('id', $vendor->permissionToUser[0]->user->id)->first();
        }
        $user->name = $request->name;
        $user->email = $request->email;
        if ($update == 'true' && $user->isDirty('email')) {
            //send reset password mail
            $this->sendResetPasswordMail($request);
        }
        if ($update == 'true' && $request->password != null) {
            $user->password = Hash::make($request->password);
        }
        $user->phone_number = $request->phone_number;
        if ($update == 'false') {
            $user->is_email_verified = 1;
            $user->is_phone_verified = 1;
        }
        $user->save();

        $checks = array();
        foreach ($request->only('name', 'address', 'latitude', 'longitude', 'desc') as $key => $value) {
            $vendor->{$key} = $value;
        }
        $vendor->dine_in = ($request->has('dine_in') && $request->dine_in == 'on') ? 1 : 0;
        $vendor->takeaway = ($request->has('takeaway') && $request->takeaway == 'on') ? 1 : 0;
        $vendor->delivery = ($request->has('delivery') && $request->delivery == 'on') ? 1 : 0;
        if ($update == 'false') {
            $vendor->logo = 'default/default_logo.png';
            $vendor->banner = 'default/default_image.png';
        }
        if ($request->hasFile('logo')) {    /* upload logo file */
            $file = $request->file('logo');
            $vendor->logo = Storage::disk('s3')->put('/vendor', $file, 'public');
        }
        if ($request->hasFile('banner')) {    /* upload logo file */
            $file = $request->file('banner');
            $vendor->banner = Storage::disk('s3')->put('/vendor', $file, 'public');
        }
        $vendor->email = $request->email;
        $vendor->website = $request->website;
        $vendor->phone_no = $request->phone_number;
        $vendor->customer_care_number = $request->customer_care_number;
        $vendor->slug = Str::slug($request->name, "-");
        $vendor->save();
        if ($user->id > 0 && $vendor->id > 0 && $update == 'false') {
            $id = $user->id;
            $data = [
                'status' => 1,
                'is_admin' => 1,
                'is_superadmin' => 0
            ];
            $client = User::where('id', $id)->update($data);

            if (UserPermissions::where('user_id', $id)->count() == 0) {
                //for updating permissions
                //$request->permissions = [1,2,3,12,17,18,19,20,21];
                //$removepermissions = UserPermissions::where('user_id', $id)->delete();
                //if ($request->permissions) {
                $userpermissions = [1, 2, 3, 12, 17, 18, 19, 20, 21];
                $addpermission = [];
                for ($i = 0; $i < count($userpermissions); $i++) {
                    $addpermission[] =  array('user_id' => $id, 'permission_id' => $userpermissions[$i]);
                }
                UserPermissions::insert($addpermission);
                //}
            }

            //for updating vendor permissions

            $addvendorpermissions = UserVendor::updateOrCreate(['user_id' =>  $id, 'vendor_id' => $vendor->id]);

            //send reset password mail
            $this->sendResetPasswordMail($request);
        }
        return $vendor->id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $vendor = Vendor::where('id', $id)->first();
        $client_preferences = ClientPreference::first();
        $vendor_docs = VendorDocs::where('vendor_id', $id)->get();
        $returnHTML = view('backend.vendor.form')->with(['client_preferences' => $client_preferences, 'vendor' => $vendor, 'vendor_docs' => $vendor_docs])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $rules = array(
            'name'          => 'required|regex:/^[a-zA-Z\s]+$/|max:150|unique:vendors,name,' . $id,
            'email'         => 'required|email|max:50|unique:vendors,email,' . $id,
            'password'      => [
                'nullable',
                'max:50',
                Password::min(8)
                    ->numbers()
                    ->letters()
            ],
            'phone_number'  => 'required|regex:/^5[0-9]{8}$/',
            'customer_care_number' => 'nullable|regex:/^5[0-9]{8}$/',
            'address'       => 'required|string',
        );
        Validator::make($request->all(), $rules)->validate();
        $vendor = Vendor::where('id', $id);
        if (Auth::user()->is_superadmin == 0) {
            $vendor = $vendor->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendor  =  $vendor->first();

        $user = User::where('id', $vendor->permissionToUser[0]->user->id)->first();
        $rules = array(
            'email'         => 'required|email|max:50|unique:users,email,' . $user->id,
            'phone_number'  => 'required|regex:/^5[0-9]{8}$/|unique:users,phone_number,' . $user->id,
        );
        Validator::make($request->all(), $rules)->validate();
        
        DB::beginTransaction();
        try {
            $saveVendor = $this->save($request, $vendor, 'true');
            if ($saveVendor > 0) {
                $dispatch_domain = $this->checkIfPickupDeliveryOnCommon();
                if ($dispatch_domain && $dispatch_domain != false) {
                    $dispatch_domain['vendor_id'] = $saveVendor;
                    $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
                    $dispatch_domain['token'] = $token;
                    $data = [];
                    $request_from_dispatch = $this->checkUpdateVendorToDispatch($dispatch_domain);
                    if ($request_from_dispatch && isset($request_from_dispatch['status']) && $request_from_dispatch['status'] == 200) {
                        DB::commit();
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Vendor updated Successfully!',
                            'data' => $saveVendor
                        ]);
                    } else {
                        DB::rollback();
                        return $request_from_dispatch;
                    }
                } else {
                    // Pickup & Delivery service is not available, but don't rollback - just commit the vendor update
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Vendor updated Successfully!',
                        'data' => $saveVendor
                    ]);
                }
            } else {
                DB::rollback();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error while updating user or vendor.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * 
     */
    public function sendResetPasswordMail($request)
    {

        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $token = Str::random(60);
            $client_name = $client->name;
            $mail_from = $data->mail_from;
            DB::table('password_resets')->insert(['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]);
            $email_template_content = '';
            $email_template = EmailTemplate::where('id', 8)->first();
            if ($email_template) {
                $email_template_content = $email_template->content;
                $email_template_content = str_ireplace("{reset_link}", url('/reset-vendor-password/' . $token), $email_template_content);
                $email_template_content = str_ireplace("{vendor-password-creation-email-banner}", asset("images/email/vendor-password-creation-email-banner.jpg"), $email_template_content);
            }
            $data = [
                'token' => $token,
                'mail_from' => $mail_from,
                'email' => $request->email,
                'client_name' => $client_name,
                'logo' => $client->logo['original'],
                'subject' => $email_template->subject,
                'email_template_content' => $email_template_content,
            ];
            try {
                dispatch(new \App\Jobs\sendEmailJob($data))->onQueue('verify_email');
            } catch (Exception $th) {
                return response()->json(['success' => $th->getCode()], 200);
            }
        }
    }
    public function postUpdateStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            Vendor::where('id', $request->vendor_id)->update(['status' => $request->status]);
            VendorCategory::where('vendor_id', $request->vendor_id)->update(['status' => 1]);
            if ($request->vendor_id > 0 && $request->status == 1) {
                $vendor = Vendor::where('id', $request->vendor_id);
                if (Auth::user()->is_superadmin == 0) {
                    $vendor = $vendor->whereHas('permissionToUser', function ($query) {
                        $query->where('user_id', Auth::user()->id);
                    });
                }
                $vendor  =  $vendor->first();
                User::where('id', $vendor->permissionToUser[0]->user->id)->update(['is_email_verified' => 1, 'is_phone_verified' => 1]);

                $dispatch_domain = $this->checkIfPickupDeliveryOnCommon();
                if ($dispatch_domain && $dispatch_domain != false) {
                    $dispatch_domain['vendor_id'] = $request->vendor_id;
                    $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
                    $dispatch_domain['token'] = $token;
                    $data = [];
                    $request_from_dispatch = $this->checkUpdateVendorToDispatch($dispatch_domain);
                    if ($request_from_dispatch && isset($request_from_dispatch['status']) && $request_from_dispatch['status'] == 200) {
                        DB::commit();
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Vendor created Successfully!',
                            'data' => $request->vendor_id
                        ]);
                    } else {
                        DB::rollback();
                        return $request_from_dispatch;
                    }
                } else {
                    DB::rollback();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pickup & Delivery service is not available.'
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Vendor Status Updated Successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /*  /**   show vendor page - config tab      */
    public function show($domain = '', $id)
    {

        $active = array();
        $categoryToggle = array();
        $user = Auth::user();
        $vendor = Vendor::findOrFail($id);



        $client_preferences = ClientPreference::first();
        $dinein_categories = VendorDineinCategory::where('vendor_id', $id)->get();
        $vendor_tables = VendorDineinTable::where('vendor_id', $id)->with('category')->get();
        foreach ($vendor_tables as $vendor_table) {
            $vendor_table->qr_url = url('/vendor/' . $vendor->slug . '/?id=' . $vendor->id . '&name=' . $vendor->name . '&table=' . $vendor_table->id);
        }
        $co_ordinates = $all_coordinates = array();
        $areas = ServiceArea::where('vendor_id', $id)->orderBy('created_at', 'DESC')->get();
        $VendorCategory = VendorCategory::where('vendor_id', $id)->where('status', 1)->pluck('category_id')->toArray();
        $zz = 1;
        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
            ->where('client_languages.client_code', Auth::user()->code)
            ->where('client_languages.is_active', 1)
            ->orderBy('client_languages.is_primary', 'desc')->get();
        foreach ($areas as $k => $v) {
            $all_coordinates[] = [
                'name' => $k . '-a',
                'coordinates' => $v->geo_coordinates
            ];
        }
        $preferences = Session::get('preferences');
        $defaultLatitude = 30.0612323;
        $defaultLongitude = 76.1239239;
        if ($preferences) {
            $defaultLatitude = $preferences['Default_latitude'];
            $defaultLongitude = $preferences['Default_longitude'];
            $defaultAddress = $preferences['Default_location_name'];
        }
        $center = [
            'lat' => $defaultLatitude,
            'lng' => $defaultLongitude
        ];
        if (!empty($all_coordinates)) {
            $center['lat'] = $all_coordinates[0]['coordinates'][0]['lat'];
            $center['lng'] = $all_coordinates[0]['coordinates'][0]['lng'];
        }
        $area1 = ServiceArea::where('vendor_id', $id)->orderBy('created_at', 'DESC')->first();
        if (isset($area1)) {
            $co_ordinates = $area1->geo_coordinates[0];
        } else {
            $co_ordinates = [
                'lat' => $defaultLatitude, //33.5362475,
                'lng' => $defaultLongitude //-111.9267386
            ];
        }
        $categories = Category::select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
            ->with('translation_one')->where('id', '>', '1')
            ->where(function ($q) use ($id) {
                $q->whereNull('vendor_id')->orWhere('vendor_id', $id);
            })->orderBy('position', 'asc')->orderBy('id', 'asc')->orderBy('parent_id', 'asc')->get();

        /* get active category list also with parent */
        foreach ($categories as $category) {
            if (in_array($category->id, $VendorCategory) && $category->parent_id == 1) {
                $active[] = $category->id;
            }
            if (in_array($category->id, $VendorCategory) && in_array($category->parent_id, $VendorCategory)) {
                $active[] = $category->id;
            }
        }
        if ($categories) {
            $build = $this->buildTree($categories->toArray());
            $categoryToggle = $this->printTreeToggle($build, $active);
        }
        $templetes = DB::table('vendor_templetes')->where('status', 1)->get();
        $returnData = array();
        $returnData['client_preferences'] = $client_preferences;
        $returnData['vendor'] = $vendor;
        $returnData['center'] = $center;
        $returnData['tab'] = 'configuration';
        $returnData['co_ordinates'] = $co_ordinates;
        $returnData['all_coordinates'] = $all_coordinates;
        $returnData['areas'] = $areas;
        $returnData['dinein_categories'] = $dinein_categories;
        $returnData['vendor_tables'] = $vendor_tables;
        $returnData['languages'] = $langs;
        $returnData['categoryToggle'] = $categoryToggle;
        $returnData['VendorCategory'] = $VendorCategory;
        $returnData['templetes'] = $templetes;
        $returnData['builds'] = $build;
        $returnData['paymentOptions'] = PaymentOption::select('id', 'code')->where('status', 1)->get();
        $returnData['itemCategories'] = ItemCategory::where(array('is_deleted' => 0, 'vendor_id' => $id))->get();
        if ((isset($preferences['subscription_mode'])) && ($preferences['subscription_mode'] == 1)) {
            $subscriptions_data = $this->getSubscriptionPlans($id);
            $returnData['subscription_plans'] = $subscriptions_data['sub_plans'];
            $returnData['subscription'] = $subscriptions_data['active_sub'];
        }
        return view('backend/vendor/show')->with($returnData);
    }

    /**   show vendor page - category tab      */
    public function vendorCategory($domain = '', $id)
    {
        $csvVendors = [];
        $vendor = Vendor::findOrFail($id);
        $VendorCategory = VendorCategory::where('vendor_id', $id)->where('status', 1)->pluck('category_id')->toArray();
        $categories = Category::with('translation_one')->select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
            ->where('id', '>', '1')
            ->where(function ($q) use ($id) {
                $q->whereNull('vendor_id')
                    ->orWhere('vendor_id', $id);
            })->orderBy('position', 'asc')->orderBy('id', 'asc')->orderBy('parent_id', 'asc')->get();
        $categoryToggle = array();
        $active = array();
        /* get active category list also with parent */
        foreach ($categories as $category) {
            if (in_array($category->id, $VendorCategory) && $category->parent_id == 1) {
                $active[] = $category->id;
            }
            if (in_array($category->id, $VendorCategory) && in_array($category->parent_id, $VendorCategory)) {
                $active[] = $category->id;
            }
        }
        if ($categories) {
            $build = $this->buildTree($categories->toArray());
            $tree = $this->printTree($build, 'vendor', $active);
            $categoryToggle = $this->printTreeToggle($build, $active);
        }
        $addons = AddonSet::with('option')->select('id', 'title', 'min_select', 'max_select', 'position')
            ->where('status', '!=', 2)
            ->where('vendor_id', $id)
            ->orderBy('position', 'asc')->get();
        $langs = ClientLanguage::with('language')->select('language_id', 'is_primary', 'is_active')
            ->where('is_active', 1)
            ->orderBy('is_primary', 'desc')->get();
        $client_preferences = ClientPreference::first();
        $templetes = DB::table('vendor_templetes')->where('status', 1)->get();

        $clientCurrency = ClientCurrency::where('is_primary', 1)->with('currency')->first();
        if (isset($clientCurrency->currency) && $clientCurrency->currency->iso_code == "AED") {
            $CurremcySymbel = $clientCurrency->currency->iso_code;
        } else {
            $CurremcySymbel = $clientCurrency->currency->symbol;
        }
        $PaymentOptions = PaymentOption::select('id', 'code')->where('status', 1)->get();
        return view('backend.vendor.vendorCategory')->with(['paymentOptions' => $PaymentOptions, 'client_preferences' => $client_preferences, "CurremcySymbel" => $CurremcySymbel, 'vendor' => $vendor, 'tab' => 'category', 'html' => $tree, 'languages' => $langs, 'addon_sets' => $addons, 'VendorCategory' => $VendorCategory, 'categoryToggle' => $categoryToggle, 'templetes' => $templetes, 'builds' => $build, 'csvVendors' => $csvVendors]);
    }

    /**   show vendor page - catalog tab      */
    public function vendorCatalog($domain = '', $id)
    {
        $product_categories = [];
        $active = array();
        $type = Type::all();
        $categoryToggle = array();
        $vendor = Vendor::where('id', $id);
        if (Auth::user()->is_superadmin == 0) {
            $vendor = $vendor->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendor  =  $vendor->first();
        if (empty($vendor))
            abort(404);

        $VendorCategory = VendorCategory::where('vendor_id', $id)->where('status', 1)->pluck('category_id')->toArray();
        $categories = Category::with('primary')->select('id', 'slug')
            ->where('id', '>', '1')->where('status', '!=', '2')->where('type_id', '1')
            ->where('can_add_products', 1)->orderBy('parent_id', 'asc')->where('status', 1)->orderBy('position', 'asc')->get();
        $products = Product::with(['media.image', 'primary', 'category.cat', 'brand', 'variant' => function ($v) {
            $v->select('id', 'product_id', 'quantity', 'price')->groupBy('product_id');
        }])->select('id', 'sku', 'vendor_id', 'is_live', 'is_new', 'is_featured', 'has_inventory', 'has_variant', 'sell_when_out_of_stock', 'Requires_last_mile', 'averageRating', 'brand_id')
            ->where('vendor_id', $id)->get();
        $product_count = $products->count();
        $published_products = $products->where('is_live', 1)->count();
        $last_mile_delivery = $products->where('Requires_last_mile', 1)->count();
        $new_products = $products->where('is_new', 1)->count();
        $featured_products = $products->where('is_featured', 1)->count();
        $categories = Category::select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
            ->where('id', '>', '1')
            ->where(function ($q) use ($id) {
                $q->whereNull('vendor_id')->orWhere('vendor_id', $id);
            })->where('status', 1)->orderBy('position', 'asc')
            ->orderBy('id', 'asc')
            ->orderBy('parent_id', 'asc')->get();
        $products = Product::with(['media.image', 'primary', 'category.cat', 'brand', 'variant' => function ($v) {
            $v->select('id', 'product_id', 'quantity', 'price')->groupBy('product_id');
        }])->select('id', 'sku', 'vendor_id', 'is_live', 'is_new', 'is_featured', 'has_inventory', 'has_variant', 'sell_when_out_of_stock', 'Requires_last_mile', 'averageRating', 'brand_id')
            ->where('vendor_id', $id)->get();
        $categories = Category::with('translation_one')->select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
            ->where('id', '>', '1')
            ->where('is_core', 1)
            ->whereNotIn('type_id', [4, 5])
            ->where(function ($q) use ($id) {
                $q->whereNull('vendor_id')->orWhere('vendor_id', $id);
            })->orderBy('position', 'asc')
            ->orderBy('id', 'asc')
            ->where('status', 1)
            ->orderBy('parent_id', 'asc')->get();
        $csvProducts = CsvProductImport::where('vendor_id', $id)->orderBy('id', 'DESC')->get();
        $csvVendors = CsvVendorImport::all();
        /*    get active category list also with parent     */
        foreach ($categories as $category) {
            if (in_array($category->id, $VendorCategory) && $category->parent_id == 1) {
                $active[] = $category->id;
            }
            if (in_array($category->id, $VendorCategory) && in_array($category->parent_id, $VendorCategory)) {
                $active[] = $category->id;
            }
        }
        if ($categories) {
            $build = $this->buildTree($categories->toArray());
            $categoryToggle = $this->printTreeToggle($build, $active);
        }
        $product_categories = VendorCategory::with('category', 'category.translation_one')->where('status', 1)->where('vendor_id', $id)->get();
        $p_categories = collect();
        $product_categories_hierarchy = '';
        if ($product_categories) {
            foreach ($product_categories as $pc) {
                $p_categories->push($pc->category);
            }
            $product_categories_build = $this->buildTree($p_categories->toArray());
            $product_categories_hierarchy = $this->printCategoryOptionsHeirarchy($product_categories_build);
        }
        $templetes = DB::table('vendor_templetes')->where('status', 1)->get();
        $client_preferences = ClientPreference::first();
        $woocommerce_detail = Woocommerce::first();
        $PaymentOptions = PaymentOption::select('id', 'code')->where('status', 1)->get();
        return view('backend.vendor.vendorCatalog')->with(['paymentOptions' => $PaymentOptions, 'new_products' => $new_products, 'featured_products' => $featured_products, 'last_mile_delivery' => $last_mile_delivery, 'published_products' => $published_products, 'product_count' => $product_count, 'client_preferences' => $client_preferences, 'vendor' => $vendor, 'VendorCategory' => $VendorCategory, 'csvProducts' => $csvProducts, 'csvVendors' => $csvVendors, 'products' => $products, 'tab' => 'catalog', 'typeArray' => $type, 'categories' => $categories, 'categoryToggle' => $categoryToggle, 'templetes' => $templetes, 'product_categories' => $product_categories_hierarchy, 'builds' => $build, 'woocommerce_detail' => $woocommerce_detail]);
    }

    /**       delete vendor       */
    public function destroy($domain = '', $id)
    {
        $vendor = Vendor::where('id', $id)->first();
        $vendor->status = 2;
        $vendor->save();
        return $this->successResponse($vendor, 'Vendor deleted successfully!');
    }

    /**     update vendor configuration data     */
    public function updateConfig(Request $request, $domain = '',  $id)
    {
        $vendor = Vendor::where('id', $id)->first();
        $msg = 'Order configuration';
        $vendor->show_slot         = ($request->has('show_slot') && $request->show_slot == 'on') ? 1 : 0;
        $vendor->auto_accept_order = ($request->has('auto_accept_order') && $request->auto_accept_order == 'on') ? 1 : 0;
        if ($request->has('order_min_amount')) {
            $vendor->order_min_amount   = $request->order_min_amount;
        }
        if ($request->has('order_pre_time')) {
            $vendor->order_pre_time     = $request->order_pre_time;
        }
        if (empty($vendor->auto_accept_order) && $request->has('auto_reject_time')) {
            $vendor->auto_reject_time = $request->auto_reject_time;
        } else {
            $vendor->auto_reject_time = "";
        }
        $vendor->payment_options = json_encode($request->payment_ids);

        $vendor->is_show_vendor_details = ($request->has('is_show_vendor_details') && $request->is_show_vendor_details == 'on') ? 1 : 0;
        $vendor->is_cancel = ($request->has('is_cancelOrder') && $request->is_cancelOrder == 'on') ? 1 : 0;
        if ($request->has('commission_percent')) {
            $vendor->commission_percent         = $request->commission_percent;
            $vendor->commission_fixed_per_order = $request->commission_fixed_per_order;
            $vendor->commission_monthly         = $request->commission_monthly;
            //$vendor->add_category = ($request->has('add_category') && $request->add_category == 'on') ? 1 : 0;
            $vendor->show_slot         = ($request->has('show_slot') && $request->show_slot == 'on') ? 1 : 0;
            $msg = 'commission configuration';
        }
        $vendor->save();
        return redirect()->back()->with('success', $msg . ' updated successfully!');
    }

    /**     Activate Category for vendor     */
    public function activeCategory(Request $request, $domain = '', $vendor_id)
    {
        $product_categories = [];
        if ($request->has('can_add_category')) {
            $vendor = Vendor::where('id', $request->vendor_id)->firstOrFail();
            $vendor->add_category = $request->can_add_category == 'true' ? 1 : 0;
            $vendor->save();
        } elseif ($request->has('assignTo')) {
            // dd($request->all());
            $vendor = Vendor::where('id', $request->vendor_id)->firstOrFail();
            $vendor->vendor_templete_id = $request->assignTo;
            $vendor->save();
        } else {
            $status = $request->status == 'true' ? 1 : 0;
            $vendor_category = VendorCategory::where('vendor_id', $request->vendor_id)->where('category_id', $request->category_id)->first();
            if ($vendor_category) {
                VendorCategory::where(['vendor_id' => $request->vendor_id, 'category_id' => $request->category_id])->update(['status' => $status]);
            } else {
                VendorCategory::create(['vendor_id' => $request->vendor_id, 'category_id' => $request->category_id, 'status' => $status]);
            }
        }
        $product_categories = VendorCategory::with('category')->where('status', 1)->where('vendor_id', $request->vendor_id)->get();
        foreach ($product_categories as $product_category) {
            $product_category->category->title = $product_category->category ? $product_category->category->translation_one->name : '';
        }
        return $this->successResponse($product_categories, 'Category setting saved successfully.');
    }

    /**     Check parent category enable status - true if all parent, false if any parent disable     */
    public function checkParentStatus(Request $request, $domain = '', $id)
    {
        $blockedCategory = VendorCategory::where('vendor_id', $id)->where('status', 0)->pluck('category_id')->toArray();
        $is_parent_disabled = $exit = 0;
        $category = Category::where('id', $request->category_id)->select('id', 'parent_id')->first();
        $parent_id = $category->parent_id;
        while ($exit == 0) {
            if ($parent_id == 1) {
                $exit = 1;
                break;
            } elseif (in_array($parent_id, $blockedCategory)) {
                $is_parent_disabled = 1;
                $exit = 1;
            } else {
                $category = Category::where('id', $parent_id)->select('id', 'parent_id')->first();
                $parent_id = $category->parent_id;
            }
        }
        if ($is_parent_disabled == 1) {
            return $this->errorResponse('Parent category is disabled. First enable parent category to enable this category.', 422);
        } else {
            return $this->successResponse(null, 'Parent is enabled.');
        }
    }

    /**
     * Import Excel file for vendors
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importCsv(Request $request)
    {
        if ($request->has('vendor_csv')) {
            $csv_vendor_import = new CsvVendorImport;
            if ($request->file('vendor_csv')) {
                $fileName = time() . '_' . $request->file('vendor_csv')->getClientOriginalName();
                $filePath = $request->file('vendor_csv')->storeAs('csv_vendors', $fileName, 'public');
                $csv_vendor_import->name = $fileName;
                $csv_vendor_import->path = '/storage/' . $filePath;
                $csv_vendor_import->status = 1;
                $csv_vendor_import->save();
            }
            $data = Excel::import(new VendorImport($csv_vendor_import->id), $request->file('vendor_csv'));
            return response()->json([
                'status' => 'success',
                'message' => 'File Successfully Uploaded!'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'File Upload Pending!'
        ]);
    }

    /**
     *update Create Vendor In Dispatch
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCreateVendorInDispatch(Request $request)
    {
        DB::beginTransaction();
        try {
            $dispatch_domain = $this->checkIfPickupDeliveryOnCommon();
            if ($dispatch_domain && $dispatch_domain != false) {
                $dispatch_domain['vendor_id'] = $request->id;
                $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
                $dispatch_domain['token'] = $token;
                $data = [];
                $request_from_dispatch = $this->checkUpdateVendorToDispatch($dispatch_domain);
                if ($request_from_dispatch && isset($request_from_dispatch['status']) && $request_from_dispatch['status'] == 200) {
                    DB::commit();
                    $request_from_dispatch['url'] = $request_from_dispatch['url'] . "?set_unique_order_login=" . $token;
                    return $request_from_dispatch;
                } else {
                    DB::rollback();
                    return $request_from_dispatch;
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pickup & Delivery service in not available.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    // check and update in dispatcher panel
    public function checkUpdateVendorToDispatch($dispatch_domain)
    {
        try {
            $vendor = Vendor::find($dispatch_domain->vendor_id);
            $unique = Auth::user()->code;
            $postdata =  [
                'vendor_id' => $dispatch_domain->vendor_id ?? 0,
                'name' => $vendor->name ?? "Manager" . $dispatch_domain->vendor_id,
                'phone_number' =>  $vendor->phone_no ?? rand('11111', '458965'),
                'email' => $unique . $vendor->id . "_dispatcher@wayno.ae",
                'team_tag' => $unique . "_" . $vendor->id,
                'public_session' => $dispatch_domain->token
            ];

            $headers = [
                'personaltoken' => $dispatch_domain->pickup_delivery_service_key,
                'shortcode' => $dispatch_domain->pickup_delivery_service_key_code,
                'content-type' => 'application/json'
            ];
            $client = new GClient(['headers' => $headers]);

            $url = $dispatch_domain->pickup_delivery_service_key_url;
            $res = $client->post(
                $url . '/api/update-create-vendor-order',
                ['form_params' => ($postdata)]
            );
            $response = json_decode($res->getBody(), true);
            if ($response) {
                return $response;
            }
            return $response;
        } catch (\Exception $e) {
            $data = [];
            $data['status'] = 400;
            $data['message'] =  $e->getMessage();
            return $data;
        }
    }



    // serach customer for vendor permission

    public function searchUserForPermission(Request $request)
    {
        $search = $request->get('query') ?? '';
        $vendor_id = $request->get('vendor_id') ?? 0;
        $alreadyids = UserVendor::where('vendor_id', $vendor_id)->pluck('user_id');
        if (isset($search)) {
            if ($search == '') {
                $employees = User::orderby('name', 'asc')->select('id', 'name', 'email', 'phone_number')->where('is_superadmin', '!=', 1)->whereNotIn('id', $alreadyids)->limit(10)->get();
            } else {
                $employees = User::orderby('name', 'asc')->select('id', 'name', 'email', 'phone_number')->where('is_superadmin', '!=', 1)->whereNotIn('id', $alreadyids)->where('name', 'LIKE', "%{$search}%")->limit(10)->get();
            }
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach ($employees as $row) {
                $output .= '
                        <li data-id="' . $row->id . '"><a href="#">' . $row->name . '(' . $row->email . ')</a></li>
                        ';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    /**
     * submit permissions for user via vendor
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissionsForUserViaVendor(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = array(
                'ids' => 'required',
            );
            $validation  = Validator::make($request->all(), $rules)->validate();

            $id = $request->ids;
            $data = [
                'status' => 1,
                'is_admin' => 1,
                'is_superadmin' => 0
            ];
            $client = User::where('id', $id)->update($data);

            if (UserPermissions::where('user_id', $id)->count() == 0) {
                //for updating permissions
                //$request->permissions = [1,2,3,12,17,18,19,20,21];
                //$removepermissions = UserPermissions::where('user_id', $id)->delete();
                //if ($request->permissions) {
                $userpermissions = [1, 2, 3, 12, 17, 18, 19, 20, 21];
                $addpermission = [];
                for ($i = 0; $i < count($userpermissions); $i++) {
                    $addpermission[] =  array('user_id' => $id, 'permission_id' => $userpermissions[$i]);
                }
                UserPermissions::insert($addpermission);
                //}
            }
            //for updating vendor permissions
            $addvendorpermissions = UserVendor::updateOrCreate(['user_id' =>  $id, 'vendor_id' => $request->vendor_id]);
            DB::commit();
            return $this->successResponse($client, 'Updated.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
    }



    /**
     *update Create Vendor In Dispatch On demand
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCreateVendorInDispatchOnDemand(Request $request)
    {
        DB::beginTransaction();
        try {
            $dispatch_domain = $this->checkIfOnDemandOnCommon();
            if ($dispatch_domain && $dispatch_domain != false) {
                $dispatch_domain['vendor_id'] = $request->id;
                $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
                $dispatch_domain['token'] = $token;
                $data = [];
                $request_from_dispatch = $this->checkUpdateVendorToDispatchOnDemand($dispatch_domain);
                if ($request_from_dispatch && isset($request_from_dispatch['status']) && $request_from_dispatch['status'] == 200) {
                    DB::commit();
                    $request_from_dispatch['url'] = $request_from_dispatch['url'] . "?set_unique_order_login=" . $token;
                    return $request_from_dispatch;
                } else {
                    DB::rollback();
                    return $request_from_dispatch;
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pickup & Delivery service in not available.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }



    // check and update in dispatcher panel on demand
    public function checkUpdateVendorToDispatchOnDemand($dispatch_domain)
    {
        try {

            $vendor = Vendor::find($dispatch_domain->vendor_id);
            $unique = Auth::user()->code;
            $postdata =  [
                'vendor_id' => $dispatch_domain->vendor_id ?? 0,
                'name' => $vendor->name ?? "Manager" . $dispatch_domain->vendor_id,
                'phone_number' =>  $vendor->phone_no ?? rand('11111', '458965'),
                'email' => $unique . $vendor->id . "_dispatcher@wayno.ae",
                'team_tag' => $unique . "_" . $vendor->id,
                'public_session' => $dispatch_domain->token
            ];

            $client = new GClient([
                'headers' => [
                    'personaltoken' => $dispatch_domain->dispacher_home_other_service_key,
                    'shortcode' => $dispatch_domain->dispacher_home_other_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);

            $url = $dispatch_domain->dispacher_home_other_service_key_url;
            $res = $client->post(
                $url . '/api/update-create-vendor-order',
                ['form_params' => (
                    $postdata
                )]
            );
            $response = json_decode($res->getBody(), true);
            if ($response) {
                return $response;
            }
            return $response;
        } catch (\Exception $e) {
            $data = [];
            $data['status'] = 400;
            $data['message'] =  $e->getMessage();
            return $data;
        }
    }




    /**
     * Remove the specified user fro vendor permission
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userVendorPermissionDestroy($domain = '', $id)
    {
        $del_price_rule = UserVendor::where('id', $id);
        $del_price_rule = $del_price_rule->delete();

        return redirect()->back()->with('success', 'Permission deleted successfully!');
    }

    /**
     * get vendor subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans($id)
    {
        $sub_plans = SubscriptionPlansVendor::with('features.feature')->where('status', '1')->orderBy('sort_order', 'asc')->get();
        $featuresList = SubscriptionFeaturesListVendor::where('status', 1)->get();
        $active_subscription = SubscriptionInvoicesVendor::with(['plan', 'features.feature', 'status'])
            ->where('vendor_id', $id)
            ->where('status_id', '!=', 4)
            ->orderBy('end_date', 'desc')
            ->orderBy('id', 'desc')->first();

        if ($sub_plans) {
            foreach ($sub_plans as $sub) {
                $subFeaturesList = array();
                if ($sub->features->isNotEmpty()) {
                    foreach ($sub->features as $feature) {
                        $subFeaturesList[] = $feature->feature->title;
                    }
                    unset($sub->features);
                }
                $sub->features = $subFeaturesList;
            }
        }
        $data['sub_plans'] = $sub_plans;
        $data['active_sub'] = $active_subscription;
        return $data;
    }

    public function addItemCategory(Request $request)
    {
        // Validate the request data, including vendor_id
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id', // Assumes vendor_id must exist in the vendors table
            'name' => 'required',
            'name.*' => 'required|string',
            'selected_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'unselected_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Define directory path
        $directoryPath = public_path('images/itemCategory');

        // Check if the directory exists, if not, create it
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        // Process and save selected image
        $selectedImageName = null;
        if ($request->hasFile('selected_image')) {
            $selectedImage = $request->file('selected_image');
            $selectedImageName = 'selected_' . time() . '.' . $selectedImage->getClientOriginalExtension();
            $selectedImage->move($directoryPath, $selectedImageName);
        }

        // Process and save unselected image
        $unselectedImageName = null;
        if ($request->hasFile('unselected_image')) {
            $unselectedImage = $request->file('unselected_image');
            $unselectedImageName = 'unselected_' . time() . '.' . $unselectedImage->getClientOriginalExtension();
            $unselectedImage->move($directoryPath, $unselectedImageName);
        }

        // Save the data to the database
        $itemCategory = new ItemCategory();
        $itemCategory->vendor_id = $request->vendor_id; // Save vendor_id
        $itemCategory->name = $request->name;
        $itemCategory->description = $request->description;
        $itemCategory->selected_image = 'images/itemCategory/' . $selectedImageName;
        $itemCategory->unselected_image = 'images/itemCategory/' . $unselectedImageName;
        $itemCategory->save();

        return response()->json(['success' => 'Item Category added successfully!']);
    }
    public function editItemCategory(Request $request)
    {
        // Validate the request data
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id', // Assumes vendor_id must exist in the vendors table
            'name' => 'required|string',
            'selected_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image is nullable for editing
            'unselected_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image is nullable for editing
        ]);

        // Find the item category by ID
        $itemCategory = ItemCategory::find($request->id);
        if (!$itemCategory) {
            return response()->json(['error' => 'Item Category not found.'], 404);
        }

        // Define directory path
        $directoryPath = public_path('images/itemCategory');

        // Check if the directory exists, if not, create it
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        // Process and save selected image (if exists)
        if ($request->hasFile('selected_image')) {
            // Delete old selected image if it exists
            if ($itemCategory->selected_image && file_exists(public_path($itemCategory->selected_image))) {
                unlink(public_path($itemCategory->selected_image));
            }

            $selectedImage = $request->file('selected_image');
            $selectedImageName = 'selected_' . time() . '.' . $selectedImage->getClientOriginalExtension();
            $selectedImage->move($directoryPath, $selectedImageName);
            $itemCategory->selected_image = 'images/itemCategory/' . $selectedImageName;
        }

        // Process and save unselected image (if exists)
        if ($request->hasFile('unselected_image')) {
            // Delete old unselected image if it exists
            if ($itemCategory->unselected_image && file_exists(public_path($itemCategory->unselected_image))) {
                unlink(public_path($itemCategory->unselected_image));
            }

            $unselectedImage = $request->file('unselected_image');
            $unselectedImageName = 'unselected_' . time() . '.' . $unselectedImage->getClientOriginalExtension();
            $unselectedImage->move($directoryPath, $unselectedImageName);
            $itemCategory->unselected_image = 'images/itemCategory/' . $unselectedImageName;
        }

        // Update the item category data
        $itemCategory->vendor_id = $request->vendor_id;
        $itemCategory->name = $request->name;
        $itemCategory->description = $request->description;
        $itemCategory->save();

        // Return success message
        return response()->json(['success' => 'Item Category updated successfully!']);
    }

    public function deleteItemCategory(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:item_categories,id',
        ]);
        $itemCategory = ItemCategory::find($request->id);
        if (!$itemCategory) {
            return response()->json(['error' => 'Item Category not found.'], 404);
        }
        $itemCategory->update([
            'is_deleted' => 1
        ]);
        return response()->json(['success' => 'Item Category marked as deleted successfully!']);
    }
}