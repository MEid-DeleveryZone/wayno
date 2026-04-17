<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Promocode, Product, Vendor, PromoType, Category, PromocodeUser, PromocodeProduct, PromocodeRestriction,PromoCodeDetail, PromocodeTranslation, ClientLanguage};
use Auth;
class PromocodeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $promocodes = Promocode::with('type', 'restriction', 'primary');

        if (Auth::user()->is_superadmin == 0) {
            $promocodes = $promocodes->where('added_by', Auth::user()->id);

        }
         // else{
        //   $promocode_ids = PromoCodeDetail::where('refrence_id' ,auth()->user()->id)->pluck('promocode_id');
        //   print_r($promocode_ids);
        //   $promocodes = $promocodes->where('restriction_on',1)->whereIn('id',$promocode_ids);
        // }
        $promocodes = $promocodes->get();
        
        $client_languages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.is_primary')
            ->where('client_languages.client_code', Auth::user()->code)
            ->where('client_languages.is_active', 1)
            ->orderBy('client_languages.is_primary', 'desc')->get();

        return view('backend/promocode/index')->with(['promocodes' => $promocodes, 'client_languages' => $client_languages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dataIds = array();
        $promocode = new Promocode();
        $promoTypes = PromoType::where('status', 1)->get();
        $products = Product::select('id', 'sku')->where('is_live', 1);
        if (Auth::user()->is_superadmin == 0) {
            $products = $products->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $products = $products->get();

        $vendors = Vendor::select('id', 'name')->where('status', 1);
        if (Auth::user()->is_superadmin == 0) {
            $vendors = $vendors->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendors = $vendors->get();
        $categories = Category::select('id', 'slug')->get();
        
        // Don't pass client_languages for create form - will use default English
        
        $returnHTML = view('backend.promocode.form')->with(['promo' => $promocode,  'promoTypes' => $promoTypes, 'categories' => $categories, 'vendors' => $vendors, 'products' => $products, 'restrictionType' => '', 'include' => '0', 'exclude' => '0', 'dataIds' => $dataIds])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain, $id){

        $rules = array(
            'name' => 'required|string|max:150||unique:promocodes,name,'.$id,
            'amount' => 'required|numeric',
            'promo_type_id' => 'required',
            'expiry_date' => 'required',
            'minimum_spend' => 'required|numeric',
            'maximum_spend' => 'required|numeric',
            'limit_per_user' => 'required|numeric',
            'limit_total' => 'required|numeric',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $promocode = Promocode::findOrFail($id);

        $promoId = $this->save($request, $promocode, 1);
        if($promoId > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Promocode updated Successfully!',
                'data' => $promoId
            ]);
        }
    }

    /**
     * save and update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, Promocode $promocode, $update = 0){

        foreach ($request->only('name', 'amount', 'expiry_date', 'promo_type_id', 'minimum_spend', 'maximum_spend', 'limit_per_user', 'limit_total', 'paid_by_vendor_admin') as $key => $value) {
            $promocode->{$key} = $value;
        }
        
        $promocode->first_order_only = ($request->has('first_order_only') && $request->first_order_only == 'on') ? 1 : 0;
        $promocode->allow_free_delivery = ($request->has('allow_free_delivery') && $request->allow_free_delivery == 'on') ? 1 : 0;
        $promocode->restriction_on = $request->restriction_on;
        $promocode->Paid_by_vendor_admin = $request->radioInline;
        $promocode->restriction_type = $request->restriction_type == 'include'?  0: 1;
        
        // Check if this is a new promocode (not an update)
        $isNew = ($update != 1); // update==1 means editing, anything else means creating
        
        if($isNew){
            $promocode->added_by = Auth::id();
        }

        $promocode->save();
        
        // Handle translations
        $language_id = null;
        
        if($request->has('language_id') && !empty($request->language_id)){
            // Use provided language_id (for edit mode)
            $language_id = $request->language_id;
        } elseif($isNew){
            // For new promocodes without language_id, set to English by default
            $english_language = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                ->where('client_languages.client_code', Auth::user()->code)
                ->where('client_languages.is_active', 1)
                ->where('lang.sort_code', 'en')
                ->select('lang.id as langId')
                ->first();
            
            $language_id = $english_language ? $english_language->langId : 1; // Default to 1 if English not found
        }
        
        if($promocode->id && !empty($language_id)){
            $promocode_translation = PromocodeTranslation::where('promocode_id', $promocode->id)
                ->where('language_id', $language_id)->first();
            
            if(!$promocode_translation){
                $promocode_translation = new PromocodeTranslation();
            }
            
            $promocode_translation->promocode_id = $promocode->id;
            $promocode_translation->language_id = $language_id;
            $promocode_translation->title = $request->title ?? '';
            $promocode_translation->short_desc = $request->short_desc ?? '';
            
            if ($request->hasFile('image')) {    /* upload logo file */
                $file = $request->file('image');
                $promocode_translation->image = Storage::disk('s3')->put('/promocode', $file,'public');
            }
            
            $promocode_translation->save();
        }
        
        if($promocode->id){
            PromoCodeDetail::where('promocode_id', $promocode->id)->delete();
            if($request->restriction_on == 0){
                $productList = $request->productList;
            }elseif($request->restriction_on == 1){
                $productList = $request->vendorList;
            }elseif($request->restriction_on == 2){
                $productList = $request->categoryList;
            }
            if($productList){
                foreach ($productList as  $refrence_id) {
                    $promo_code_detail = new PromoCodeDetail();
                    $promo_code_detail->promocode_id = $promocode->id;
                    $promo_code_detail->refrence_id = $refrence_id;
                    $promo_code_detail->save();
                }
            }
        }
        return $promocode->id;
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
            'expiry_date' => 'required',
            'promo_type_id' => 'required',
            'amount' => 'required|numeric',
            'limit_total' => 'required|numeric',
            'minimum_spend' => 'required|numeric',
            'maximum_spend' => 'required|numeric',
            'limit_per_user' => 'required|numeric',
            'name' => 'required|string|max:150||unique:promocodes',
        );
        if ($request->hasFile('image')) {    /* upload logo file */
            $rules['image'] =  'image|mimes:jpeg,png,jpg,gif';
        }
        $validation  = Validator::make($request->all(), $rules)->validate();
        $promocode = new Promocode();
        $promoId = $this->save($request, $promocode, 'false');
        if($promoId > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Promocode created Successfully!',
                'data' => $promoId
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function edit($domain, $id){
        $dataIds = array();
        $promoTypes = PromoType::where('status', 1)->get();

        $products = Product::select('id', 'sku')->where('is_live', 1);
        if (Auth::user()->is_superadmin == 0) {
            $products = $products->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $products = $products->get();

        $vendors = Vendor::select('id', 'name')->where('status', 1);
        if (Auth::user()->is_superadmin == 0) {
            $vendors = $vendors->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendors = $vendors->get();

        $promocode = Promocode::with('restriction', 'primary', 'details')->where('id', $id)->first();
        
        if (!$promocode) {
            return response()->json(array('success' => false, 'message' => 'Promocode not found'));
        }
        
        $categories = Category::with('english')->select('id', 'slug')
            ->where('id', '>', '1')->where('status', '!=', '2')
            ->where('can_add_products', 1)->orderBy('parent_id', 'asc')
            ->orderBy('position', 'asc')->get();
        
        if($promocode->details) {
            foreach ($promocode->details as $detail) {
                $dataIds[] = $detail->refrence_id;
            }
        }
        
        $client_languages = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.is_primary')
            ->where('client_languages.client_code', Auth::user()->code)
            ->where('client_languages.is_active', 1)
            ->orderBy('client_languages.is_primary', 'desc')->get();
        
        $returnHTML = view('backend.promocode.form')->with(['promo' => $promocode, 'promoTypes' => $promoTypes, 'dataIds' => $dataIds, 'categories' => $categories, 'vendors' => $vendors, 'products' => $products, 'client_languages' => $client_languages])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Show the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $domain, $id){
        $language_id = $request->language_id;
        $promocode = Promocode::with(array('translation' => function($query) use($language_id) {
            $query->where('language_id', $language_id);
        }))->where('id', $id)->first();
        return response()->json(['success' => true, 'data' => $promocode]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain, $id){
        Promocode::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Promocode deleted successfully!');
    }
}
