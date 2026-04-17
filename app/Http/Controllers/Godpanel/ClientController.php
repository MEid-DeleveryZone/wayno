<?php

namespace App\Http\Controllers\Godpanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{ClientPreference, Currency, Client, Language,BusinessType};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Jobs\{ProcessClientDatabase, EditClient};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\ApiResponser;
use App\Models\{AddonOption, AddonOptionTranslation, AddonSet, AddonSetTranslation, OrderVendorProduct, Banner, MobileBanner, Brand, BrandCategory, BrandTranslation, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Category, CategoryHistory, CategoryTranslation, Celebrity, CsvProductImport, CsvVendorImport, LoyaltyCard, Order, OrderProductAddon, OrderProductPrescription, OrderProductRating, OrderProductRatingFile, OrderReturnRequest, OrderReturnRequestFile, OrderTax, OrderVendor, Payment, PaymentOption, Product, ProductAddon, ProductCategory, ProductCelebrity, ProductCrossSell, ProductImage, ProductInquiry, ProductRelated, ProductTranslation, ProductUpSell, ProductVariant, ProductVariantImage, ProductVariantSet, Promocode, PromoCodeDetail, PromocodeRestriction, ServiceArea, SlotDay, SocialMedia, Transaction, User, UserAddress, UserDevice, UserLoyaltyPoint, UserPermissions, UserRefferal, UserVendor, UserWishlist, Variant, VariantCategory, VariantOption, VariantOptionTranslation, VariantTranslation, Vendor, VendorCategory, VendorMedia, VendorOrderStatus, VendorSlot, VendorSlotDate, Wallet,CabBookingLayout,CabBookingLayoutCategory,CabBookingLayoutTranslation};
use Exception;
use Spatie\DbDumper\Databases\MySql;

class ClientController extends Controller{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $clients = Client::where('is_deleted', 0)->orderBy('created_at', 'DESC')->paginate(200);
        foreach ($clients as $client) {
            $client->sub_domain_url = 'https://'.$client->sub_domain.env('SUBMAINDOMAIN');
        }
        return view('godpanel/client')->with(['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::where('id', '>', '0')->get();
        $business_types = BusinessType::get();
       
        return view('godpanel/client-form')->with(['languages' => $languages,'business_types' => $business_types]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::find($id);
        $languages = Language::where('id', '>', '0')->get();
        $business_types = BusinessType::get();
      return view('godpanel/client-form-update')->with(['client' => $client, 'languages' => $languages, 'business_types' => $business_types]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $client = new Client();
        $validation  = Validator::make($request->all(), $client->rules());
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        DB::beginTransaction();
        try {
        $data = $this->saveClient($request, $client, 'false');
        if(!$data){
            return redirect()->back()->withErrors(['error' => "Something went wrong."]);
        }
        $business_type = $request->business_type??null;
      
        $update = DB::table('clients')->where('id',$data->id)->update(['business_type' => $business_type]);
        $database_name = preg_replace('/\s+/', '', $request->database_name);
        Cache::set($database_name, $data);
        $languId = ($request->has('primary_language')) ? $request->primary_language : 1;
        DB::commit();
        $this->dispatchNow(new ProcessClientDataBase($data->id, $languId,$business_type));
        return redirect()->route('client.index')->with('success', 'Client Added successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('client.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $save = $this->saveClient($request, $client, 'true');

        if(!$save){
            return redirect()->back()->withErrors(['error' => "Something went wrong."]);
        }
        $update = DB::table('clients')->where('id',$id)->update(['business_type' => $request->business_type]);
        $this->dispatchNow(new EditClient($client->id));
        return redirect()->route('client.index')->with('success', 'Client Updated successfully!');
    }

    /* save and update client information */
    public function saveClient(Request $request, Client $client, $update = 'false')
    {
        foreach ($request->only('name', 'phone_number', 'company_name', 'company_address', 'custom_domain', 'sub_domain', 'database_host', 'database_port', 'database_username', 'database_password') as $key => $value) {
            $client->{$key} = $value;
        }

        $client->database_host = env('DB_HOST');
        $client->database_port = '3306';
        $client->database_username = env('DB_USERNAME');
        $client->database_password = env('DB_PASSWORD');

        if($update == 'false'){
            $client->logo = 'default/default_logo.png';
            $client->database_path = '';
            //$client->database_username = env('DB_USERNAME');
            //$client->database_password = env('DB_PASSWORD');
           
            $client->email = $request->email;
            $client->database_name = $request->database_name;
            $client->password = Hash::make($request->encpass);
            $client->encpass = $request->encpass;
            $client->code = $this->randomString();
            $client->country_id = $request->country ? $request->country : NULL;
            $client->timezone = $request->timezone ? $request->timezone : NULL;
            $client->business_type = $request->business_type ?? NULL;
            $client->status = 1;
        }
        $isPasswordUpdate = 0;
        if($update == 'true'){
            if($request->has('encpass') && !empty($request->encpass)){
                $client->password = Hash::make($request->encpass);
                $client->encpass = $request->encpass;
                $isPasswordUpdate = 1;
            }
        }
        if ($request->hasFile('logo')) {    /* upload logo file */
            $file = $request->file('logo');
            $client->logo = Storage::disk('s3')->put('Clientlogo', $file, 'public');
        }
        $client->save();
        return $client;
    }
    
    /* Create random and unique client code*/
    private function randomString(){
        $random_string = substr(md5(microtime()), 0, 6);
        // after creating, check if string is already used

        while(Client::where('code', $random_string )->exists()){
            $random_string = substr(md5(microtime()), 0, 6);
        }
        return $random_string;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        return redirect()->back()->with(['getClient' => $client]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $client = Client::where('id', $id)->first();
        $client->status = $request->action;
        $msg = '';
        if($request->action == 3){
            $client->is_deleted = 1;
            $msg = 'deleted';
        }else{
            $client->is_blocked = ($request->action == 2) ? 1 : 0;
            $msg = ($request->action == 2) ? 'blocked' : 'unblocked';
        }
        $client->save();
        return redirect()->back()->with('success', 'Client account ' . $msg . ' successfully!');
    }

    public function remove(Request $request){
        $client = Client::where('id', $request->client_id)->first();
        $cmd =  \DB::statement("DROP DATABASE `royo_".$client->database_name."`");
        $client->delete();
        return $this->successResponse(['status'=>'success', 'message' => 'Client account deleted successfully!'], '', 200);
    }

    /**
     * Store/Update Client Preferences 
     */
    public function storePreference(Request $request, $id)
    {
        $client = Client::where('code', $id)->firstOrFail();
        //update the client custom_domain if value is set //
        if ($request->domain_name == 'custom_domain') {
            // check the availability of the domain //
            $exists = Client::where('code', '<>', $id)->where('custom_domain', $request->custom_domain_name)->count();
            if ($exists) {
                return redirect()->back()->withErrors(new \Illuminate\Support\MessageBag(['domain_name' => 'Domain name "' . $request->custom_domain_name . '" is not available. Please select a different domain']));
            }
            Client::where('id', $id)->update(['custom_domain' => $request->custom_domain_name]);
        }
        
        $updatePreference = ClientPreference::updateOrCreate([
            'client_id' => $id
        ], $request->all());
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Preference updated successfully!',
                'data' => $updatePreference
            ]);
        } else {
            return redirect()->back()->with('success', 'Preference updated successfully!');
        }
    }

    /**
     * Store/Update Client Preferences 
     */
    public function ShowPreference()
    {
        $preference = ClientPreference::where('client_id', Auth::user()->code)->first();
        $currencies = Currency::orderBy('iso_code')->get();
        return view('customize')->with(['preference' => $preference, 'currencies' => $currencies]);
    }


    /**
     * Show Configuration page 
     */
    public function ShowConfiguration()
    {
        $preference = ClientPreference::where('client_id',Auth::user()->code)->first();
        $client = Auth::user();
        return view('configure')->with(['preference' => $preference, 'client' => $client]);
    }

    /**
     * Show Options page 
     */
    public function ShowOptions()
    {
        $preference = ClientPreference::where('client_id',Auth::user()->id)->first();
        return view('options')->with(['preference' => $preference]);
    }










    /////////////// *********************** migrate Default********************************* ////////////////////////////////////////

    public function migrateDefaultData(Request $request,$id)
    {
        try {
            
            if (isset($request->business_type) && !empty($request->business_type)) {
                $client = Client::find($id);

                $schemaName = 'royo_' . $client->database_name;
                $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST', '127.0.0.1');
                $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT', '3306');
                $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME', 'root');
                $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD', '');

                $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => $database_host,
                'port' => $database_port,
                'database' => $schemaName,
                'username' => $database_username,
                'password' => $database_password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
            ];
           
                Config::set("database.connections.$schemaName", $default);
                config(["database.connections.mysql.database" => $schemaName]);
            
                DB::connection($schemaName)->beginTransaction();
                DB::connection($schemaName)->statement("SET foreign_key_checks=0");
                Cart::on($schemaName)->truncate();
                Brand::on($schemaName)->truncate();
                Order::on($schemaName)->truncate();
                Banner::on($schemaName)->truncate();
                MobileBanner::on($schemaName)->truncate();
                Vendor::on($schemaName)->truncate();
                SlotDay::on($schemaName)->truncate();
                Payment::on($schemaName)->truncate();
                Variant::on($schemaName)->truncate();
                Product::on($schemaName)->truncate();
                AddonSet::on($schemaName)->truncate();
                Category::on($schemaName)->truncate();
                OrderTax::on($schemaName)->truncate();
                Promocode::on($schemaName)->truncate();
                CartAddon::on($schemaName)->truncate();
                Celebrity::on($schemaName)->truncate();
                VendorSlot::on($schemaName)->truncate();
                CartCoupon::on($schemaName)->truncate();
                AddonOption::on($schemaName)->truncate();
                LoyaltyCard::on($schemaName)->truncate();
                ServiceArea::on($schemaName)->truncate();
                VendorMedia::on($schemaName)->truncate();
                CartProduct::on($schemaName)->truncate();
                SocialMedia::on($schemaName)->truncate();
                Transaction::on($schemaName)->truncate();
                OrderVendor::on($schemaName)->truncate();
                ProductAddon::on($schemaName)->truncate();
                ProductImage::on($schemaName)->truncate();
                ProductUpSell::on($schemaName)->truncate();
                VariantOption::on($schemaName)->truncate();
                BrandCategory::on($schemaName)->truncate();
                VendorSlotDate::on($schemaName)->truncate();
                VendorCategory::on($schemaName)->truncate();
                ProductRelated::on($schemaName)->truncate();
                ProductVariant::on($schemaName)->truncate();
                ProductInquiry::on($schemaName)->truncate();
                ProductCategory::on($schemaName)->truncate();
                CsvVendorImport::on($schemaName)->truncate();
                VariantCategory::on($schemaName)->truncate();
                PromoCodeDetail::on($schemaName)->truncate();
                CategoryHistory::on($schemaName)->truncate();
                CsvProductImport::on($schemaName)->truncate();
                BrandTranslation::on($schemaName)->truncate();
                ProductCelebrity::on($schemaName)->truncate();
                ProductCrossSell::on($schemaName)->truncate();
                ProductVariantSet::on($schemaName)->truncate();
                VendorOrderStatus::on($schemaName)->truncate();
                OrderProductAddon::on($schemaName)->truncate();
                OrderProductRating::on($schemaName)->truncate();
                ProductTranslation::on($schemaName)->truncate();
                VariantTranslation::on($schemaName)->truncate();
                OrderVendorProduct::on($schemaName)->truncate();
                OrderReturnRequest::on($schemaName)->truncate();
                AddonSetTranslation::on($schemaName)->truncate();
                CategoryTranslation::on($schemaName)->truncate();
                ProductVariantImage::on($schemaName)->truncate();
                PromocodeRestriction::on($schemaName)->truncate();
                AddonOptionTranslation::on($schemaName)->truncate();
                OrderProductRatingFile::on($schemaName)->truncate();
                OrderReturnRequestFile::on($schemaName)->truncate();
                CartProductPrescription::on($schemaName)->truncate();
                CartProductPrescription::on($schemaName)->truncate();
                VariantOptionTranslation::on($schemaName)->truncate();
                OrderProductPrescription::on($schemaName)->truncate();
                CabBookingLayout::on($schemaName)->truncate();
                CabBookingLayoutCategory::on($schemaName)->truncate();
                CabBookingLayoutTranslation::on($schemaName)->truncate();
               
                $sql_file = $request->business_type;


                

                DB::connection($schemaName)->unprepared(file_get_contents((asset('sql_files/'.$sql_file))));

                $busines = ucwords(str_replace("_", " ", $request->business_type));

                DB::connection($schemaName)->commit();
                DB::connection($schemaName)->statement("SET foreign_key_checks=1");
            
                return redirect()->route('client.index')->with('success', $busines.' Data added successfully!');
           
            }
        } catch (\PDOException $e) {
            DB::connection($schemaName)->rollBack();
            return redirect()->route('client.index')->with('error', $e->getMessage());
        }
            
            
    }

     /////////////// *********************** single Vendor Setting********************************* ////////////////////////////////////////

     public function singleVendorSetting(Request $request,$id)
     {
         try {
             
            // if (isset($request->single_vendor) && !empty($request->single_vendor)) {
                 $client = Client::find($id);
 
                 $schemaName = 'royo_' . $client->database_name;
                 $database_host = !empty($client->database_host) ? $client->database_host : env('DB_HOST', '127.0.0.1');
                 $database_port = !empty($client->database_port) ? $client->database_port : env('DB_PORT', '3306');
                 $database_username = !empty($client->database_username) ? $client->database_username : env('DB_USERNAME', 'root');
                 $database_password = !empty($client->database_password) ? $client->database_password : env('DB_PASSWORD', '');
 
                 $default = [
                 'driver' => env('DB_CONNECTION', 'mysql'),
                 'host' => $database_host,
                 'port' => $database_port,
                 'database' => $schemaName,
                 'username' => $database_username,
                 'password' => $database_password,
                 'charset' => 'utf8mb4',
                 'collation' => 'utf8mb4_unicode_ci',
                 'prefix' => '',
                 'prefix_indexes' => true,
                 'strict' => false,
                 'engine' => null
                ];
            
                 Config::set("database.connections.$schemaName", $default);
                 config(["database.connections.mysql.database" => $schemaName]);
             
                 DB::connection($schemaName)->beginTransaction();

                 $update = DB::table('clients')->where('id',$id)->update(['single_vendor' => $request->single_vendor]);
                 $update_sub = DB::connection($schemaName)->table('client_preferences')->where('id',1)->update(['single_vendor' => $request->single_vendor]);

                 DB::connection($schemaName)->commit();
                 return redirect()->route('client.index')->with('success', 'Client updated successfully!');
            
             //}
         } catch (\PDOException $e) {
             DB::connection($schemaName)->rollBack();
             return redirect()->route('client.index')->with('error', $e->getMessage());
         }
             
             
     }
}
