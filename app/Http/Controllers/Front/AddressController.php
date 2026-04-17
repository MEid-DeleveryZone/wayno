<?php

namespace App\Http\Controllers\Front;

use App\Models\{Country, UserWishlist, User, Product, UserAddress};
use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use Carbon\Carbon;
use Auth;
use Session;

class AddressController extends FrontController{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $domain = ''){
        $langId = Session::get('customerLanguage');
        $countries = Country::get();
        $useraddress = UserAddress::where('user_id', Auth::user()->id)->with('country')->get();
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/addressbook')->with(['useraddress' => $useraddress, 'navCategories' => $navCategories, 'countries'=>$countries]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, $domain = ''){
        $address = new UserAddress();
        $langId = Session::get('customerLanguage');
        $countries = Country::all();
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/editAddress')->with(['navCategories' => $navCategories,'countries' => $countries, 'address' => $address]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = ''){
        $validatedData = $request->validate([
                'type' => 'required',
                // 'city' => 'required',
                // 'state' => 'required',
                // 'pincode' => 'required',
                'address' => 'required',
                'country' => 'required',
        ], [
            'address.required' => __('The address field is required.'),
            'type.required' => __('Address Type is required'),
            'city.required' => __('The city field is required.'),
            'state.required' => __('The state field is required.'),
            'pincode.required' => __('The pincode field is required.'),
        ]);
        $country = Country::select('code', 'name')->where('id', $request->country)->first();
        $address = new UserAddress;
        $address->type = $request->type;
        $address->city = $request->city??"";
        $address->state = $request->state??"";
        $address->street = $request->street;
        $address->country = $country->name;
        $address->country_code = $country->code;
        $address->user_id = Auth::user()->id;
        $address->address = $request->address;
        $address->pincode = $request->pincode??"";
        $address->latitude  = $request->latitude;
        $address->longitude  = $request->longitude;
        $address->house_number = $request->house_number??"";
        $address->save();
        if($request->ajax()){
            return response()->json(['status' => 'success', 'message' => __('Address Has Been Added Successfully'), 'address' => $address]);
        }else{
            return redirect()->route('user.addressBook')->with('success', __('Address Has Been Added Successfully'));
        }
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update($domain = '', Request $request, $id){
        $validatedData = $request->validate([
            'type' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'address' => 'required',
            'country' => 'required',
        ], [
            'type.required' => __('Address Type is required')
        ]);
        $country = Country::select('code', 'name')->where('id', $request->country)->first();
        $user = User::where('id', Auth::user()->id)->first();
        if ($user){
            $user->country_id = $request->country;
            $user->save();
        }
        $address = UserAddress::find($id);
        $address->type = $request->type;
        $address->address = $request->address;
        $address->street = $request->street;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->country = $country->name;
        $address->country_code = $country->code;
        $address->pincode = $request->pincode;
        $address->latitude  = $request->latitude;
        $address->longitude  = $request->longitude;
        $address->house_number = $request->house_number??"";
        $address->save();
        return redirect()->route('user.addressBook')->with('success', __('Address Has Been Updated Successfully'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id){
        $address = UserAddress::findOrFail($id);
        $langId = Session::get('customerLanguage');
        $countries = Country::all();
        $navCategories = $this->categoryNav($langId);
        return view('frontend/account/editAddress')->with(['navCategories' => $navCategories,'countries' => $countries, 'address' => $address]);
    }

    /**
     * Get address details by id
     *
     * @return \Illuminate\Http\Response
     */
    public function address($domain = '', $id){
        $address = UserAddress::leftJoin('countries', 'user_addresses.country', 'countries.name')
                    ->select('user_addresses.*','countries.id as country_id')
                    ->where('user_addresses.id', $id)->first();
        $countries = Country::all();
        return response()->json(['status' => 'success', 'countries' => $countries, 'address' => $address]);
    }
   
    /**
     * Set Primary Address for user
     *
     * @return \Illuminate\Http\Response
     */
    public function setPrimaryAddress($domain = '', $id)
    {
        $user = Auth::user();
        $address = UserAddress::where('user_id', $user->id)->update(['is_primary' => 0]);
        $address = UserAddress::where('user_id', $user->id)->where('id', $id)->update(['is_primary' => 1]);
        return redirect()->route('user.addressBook')->with('success', __('Primary Address Has Been Changed Successfully'));
    }

    /**
     * delete address of user
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($domain = '', $id){
        $address = UserAddress::find($id)->delete();
        return redirect()->route('user.addressBook')->with('success', __('Address Has Been Deleted Successfully'));
    }
   

}
