<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Validator;
use App\Models\{TaxRate, TaxCategory, TaxRateCategory};
use Illuminate\Support\Facades\Storage;

class TaxCategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taxCates = TaxCategory::orderBy('id', 'desc')->get();

        $taxRates = TaxRate::with('category')->orderBy('id', 'desc')->get();
       // dd($taxRates->toArray());
        
        return view('backend/tax/index')->with(['taxCates' => $taxCates, 'taxRates' => $taxRates]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaxCategory  $taxCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $vendors = array();
        $categories = array();
        $tax = TaxCategory::where('id', $id)->first();
        $returnHTML = view('backend.tax.cate-form')->with(['tc' => $tax])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
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
            'title' => 'required|string|max:60',
            'code' => 'required|string|max:60||unique:tax_categories',
        );
        $validation  = Validator::make($request->all(), $rules)->validate();

        $tax = new TaxCategory();
        $tax->title = $request->title;
        $tax->code = $request->code;
        $tax->description = $request->description;
        $tax->save();
        if($tax->id > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Tax category created Successfully!',
                'data' => $tax
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaxCategory  $taxCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $rules = array(
            'title' => 'required|string|max:60',
            'code' => 'required|string|max:60||unique:tax_categories,code,'.$id,
        );
        $validation  = Validator::make($request->all(), $rules)->validate();
        $tax = TaxCategory::where('id', $id)->firstOrFail();
        $tax->title = $request->title;
        $tax->code = $request->code;
        $tax->description = $request->description;
        $tax->save();
        
        return response()->json([
            'status'=>'success',
            'message' => 'Tax category updated Successfully!',
            'data' => $tax
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaxCategory  $taxCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        $tax = TaxCategory::where('id', $id)->delete();
        return redirect('client/tax')->with('success', 'Tax category updated successfully!');
    }
}
