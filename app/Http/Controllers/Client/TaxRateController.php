<?php

namespace App\Http\Controllers\Client;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{TaxRate, TaxCategory, TaxRateCategory};

class TaxRateController extends BaseController{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'identifier' => 'required|string|max:25|unique:tax_rates',
            'tax_rate' => 'required|regex:/^\d*(\.\d{2})?$/',
            'category' => 'required',
        );
        // if($request->postal_type == 1){
        //     $rules['postal_code'] = 'required|min:5|max:6';
        // }
        // if($request->postal_type == 2){
        //     $rules['postal_code_start'] = 'required|min:5|max:6';
        //     $rules['postal_code_end'] = 'required|min:5|max:6';
        // }
        $validation  = Validator::make($request->all(), $rules)->validate();
        $taxRate = new TaxRate();
        $taxRate->state = $request->state;
        $taxRate->country = $request->country;
        $taxRate->tax_rate = $request->tax_rate;
        //$taxRate->is_zip = $request->postal_type;
        $taxRate->identifier = $request->identifier;
        $taxRate->zip_code = ($request->postal_type == 1) ? $request->postal_code : '';
        $taxRate->zip_to = ($request->postal_type == 2) ? $request->postal_code_end : '';
        $taxRate->zip_from = ($request->postal_type == 2) ? $request->postal_code_start : '';
        $taxRate->save();
        if($taxRate->id > 0){
            $dataCate = array();
            foreach ($request->category as $key => $value) {
                $dataCate[] = [
                    'tax_rate_id' => $taxRate->id,
                    'tax_cate_id' => $value,
                ];
            }
            TaxRateCategory::insert($dataCate);
            return response()->json([
                'status'=>'success',
                'message' => 'Tax rate added Successfully!',
                'data' => $taxRate
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaxRate  $taxRate
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $category = array();
        $taxRate = TaxRate::where('id', $id)->firstOrFail();
        $taxCates = TaxCategory::orderBy('id', 'desc')->get();
        $categories = TaxRateCategory::select('tax_cate_id')->where('tax_rate_id', $id)->get();
        foreach ($categories as $key => $value) {
            $category[] = $value->tax_cate_id;
        }
        $returnHTML = view('backend.tax.rate-form')->with(['taxRate' => $taxRate, 'category' => $category, 'taxCates' => $taxCates])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaxRate  $taxRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id){
        $rules = array(
            'identifier' => 'required|string|max:25|unique:tax_rates,identifier,'.$id,
            'tax_rate' => 'required|regex:/^\d*(\.\d{2})?$/',
            'category' => 'required',
        );
        // if($request->postal_type == 1){
        //     $rules['postal_code'] = 'required|min:5|max:6';
        // }
        // if($request->postal_type == 2){
        //     $rules['postal_code_start'] = 'required|min:5|max:6';
        //     $rules['postal_code_end'] = 'required|min:5|max:6';
        // }
        $validation  = Validator::make($request->all(), $rules)->validate();
        $taxRate = TaxRate::findOrFail($id);
        $taxRate->identifier = $request->identifier;
       // $taxRate->is_zip = $request->postal_type;
        $taxRate->zip_code = ($request->postal_type == 1) ? $request->postal_code : '';
        $taxRate->zip_from = ($request->postal_type == 2) ? $request->postal_code_start : '';
        $taxRate->zip_to = ($request->postal_type == 2) ? $request->postal_code_end : '';
        $taxRate->state = $request->state;
        $taxRate->country = $request->country;
        $taxRate->tax_rate = $request->tax_rate;
        $taxRate->save();
        $exist = array();
        foreach ($request->category as $key => $value) {
            $trc = TaxRateCategory::where('tax_rate_id', $taxRate->id)->where('tax_cate_id', $value)->first();
            if(!$trc){
                $trc = new TaxRateCategory();
                $trc->tax_rate_id = $taxRate->id;
                $trc->tax_cate_id = $value;
                $trc->save();
            }
            $exist[] = $value;
        }
        $delete = TaxRateCategory::where('tax_rate_id', $taxRate->id)->whereNotIn('tax_cate_id', $exist)->delete();
        return response()->json([
            'status'=>'success',
            'message' => 'Tax rate updated Successfully!',
            'data' => $taxRate
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaxRate  $taxRate
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        $tax = TaxRate::where('id', $id)->delete();
        return redirect('client/tax')->with('success', 'Tax category updated successfully!');
    }
}
