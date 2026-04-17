<?php

namespace App\Http\Controllers\Client;

use DB;
use Dotenv\Loader\Loader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, ClientCurrency, ClientPreference, LoyaltyCard};

class LoyaltyController extends BaseController
{
    private $folderName = '/loyalty/image';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = 0;
        $client_preferences = ClientPreference::first();
        $loyaltycards = LoyaltyCard::where('status', '!=', '2')->get();
        $client_cur = ClientCurrency::where('is_primary',1)->first();
        // dd($loyaltycards->toArray());
        $status = $client_preferences ? $client_preferences->loyalty_check : 0;
        return view('backend/loyality/index')->with(['loyaltycards' => $loyaltycards, 'status' => $status,'client_cur' => $client_cur]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'name' => 'required|string|max:150|unique:loyalty_cards',
            'minimum_points' => 'required|numeric',
            'description' => 'required|string',
            'per_order_points' => 'required|numeric',
            // 'per_purchase_minimum_amount' => 'required|numeric',
            'amount_per_loyalty_point' => 'required|numeric',
        );

        $validation  = Validator::make($request->all(), $rules)->validate();

        $loyaltyCard = new LoyaltyCard;

        $loyaltyCard->name = $request->input('name');
        $loyaltyCard->description = $request->input('description');
        $loyaltyCard->minimum_points = $request->input('minimum_points');
        $loyaltyCard->per_order_points = $request->input('per_order_points');
        $loyaltyCard->amount_per_loyalty_point = $request->input('amount_per_loyalty_point');
        $loyaltyCard->status = '0';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $loyaltyCard->image = Storage::disk('s3')->put($this->folderName, $file,'public');
        }

        $loyaltyCard->save();
        if ($loyaltyCard->id > 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'Loyalty card created Successfully!',
                'data' => $loyaltyCard
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $loyaltyCard = LoyaltyCard::where('id', $id)->first();
        $returnHTML = view('backend.loyality.form')->with(['lc' => $loyaltyCard])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($domain = '', Request $request, $id)
    {
        $rules = array(
            'name' => 'required|string|max:150|unique:loyalty_cards,name,' . $id,
            'minimum_points' => 'required|numeric',
            'description' => 'required|string',
            'per_order_points' => 'required|numeric',
            'amount_per_loyalty_point' => 'required|numeric',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $loyaltyCard = LoyaltyCard::where('id', $id)->firstOrFail();
        $loyaltyCard->name = $request->input('name');
        $loyaltyCard->description = $request->input('description');
        $loyaltyCard->minimum_points = $request->input('minimum_points');
        $loyaltyCard->per_order_points = $request->input('per_order_points');
        $loyaltyCard->amount_per_loyalty_point = $request->input('amount_per_loyalty_point');
        if(!empty($loyaltyCard->image)){
            Storage::disk('s3')->delete($loyaltyCard->image);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $loyaltyCard->image = Storage::disk('s3')->put($this->folderName, $file,'public');
        }
        $loyaltyCard->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Loyalty Card updated Successfully!',
            'data' => $loyaltyCard
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        LoyaltyCard::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Loyalty Card deleted successfully!');
    }

    /**
     * Change the status of Loyalty card.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $domain = '')
    {
        $loyaltyCard = LoyaltyCard::find($request->id);
        $loyaltyCard->status = $request->status;
        $loyaltyCard->save();
    }

    /**
     * Get the default value of Redeem Point
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRedeemPoints($domain = '')
    {
        $currency = ClientCurrency::where('is_primary', '=', 1)->first();
        $loyaltyCard = LoyaltyCard::firstOrFail();
        if ($loyaltyCard->redeem_points_per_primary_currency == null) {
            return response()->json(['symbol' => $currency->currency->symbol,'value' => '0']);
        }else {
            return response()->json(['symbol' => $currency->currency->symbol,'value' => $loyaltyCard->redeem_points_per_primary_currency]);
        }
    }

     /**
     * Get the default value of Redeem Point
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setLoyaltyCheck(Request $request)
    {
        $client_preferences = ClientPreference::first();
        if($client_preferences){
            $client_preferences->loyalty_check = $request->status;
            $client_preferences->save();
        }
        $update = LoyaltyCard::where('id', '>', 0)->update(['loyalty_check' => $request->status]);
    }

    /**
     * set the default value of Redeem Point
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setRedeemPoints(Request $request){
        $update = LoyaltyCard::where('id', '>', 0)->update(['redeem_points_per_primary_currency' => $request->redeem_points_per_primary_currency]);
        return redirect()->back()->with('success', 'Successfully Updated!');
    }
}
