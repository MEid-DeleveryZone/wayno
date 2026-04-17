<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\{AddonOption, AddonSet, AddonOptionTranslation, AddonSetTranslation, ClientLanguage};
use Illuminate\Support\Facades\Storage;

class AddonSetController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $count = count($request->price);
        $min = ($request->min_select < 1) ? 0 : $request->min_select;
        $max = ($request->max_select < 1) ? 1 : $request->max_select;

        $min = ($min > $count) ? $count : $min;
        $max = ($max > $count) ? $count : $max;
        $max = ($max < $min) ? $min : $max;

        $addOn = new AddonSet();
        $addOn->title = $request->title[0];
        $addOn->min_select = $min;
        $addOn->max_select = $max;
        $addOn->position = 1;
        $addOn->vendor_id = $request->vendor_id;
        $addOn->save();
        if($addOn->id > 0){
            $setTrans = $optTrans = array();

            foreach ($request->language_id as $lk => $lang) {
                $setTrans[] = [
                    'title' => $request->title[$lk],
                    'addon_id' => $addOn->id,
                    'language_id' => $lang,
                ];
            }
            AddonSetTranslation::insert($setTrans);
            
            foreach ($request->price as $key => $value) {

                $option = new AddonOption();
                $option->title = $request->opt_value[0][$key];
                $option->addon_id = $addOn->id;
                $option->position = $key + 1;
                $option->price = $value;
                $option->save();

                foreach ($request->language_id as $lk => $lang) {
                    $optTrans[] = [
                        'title' => $request->opt_value[$lk][$key],
                        'addon_opt_id' => $option->id,
                        'language_id' => $lang,
                    ];
                }
            }

            AddonOptionTranslation::insert($optTrans);

            return redirect()->back()->with('success', 'Variant added successfully!');
        }else{
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AddonOption  $addonOption
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $addon = AddonSet::with('translation', 'option.translation')->where('id', $id)->firstOrFail();
        $langs = ClientLanguage::with('language')->select('language_id', 'is_primary', 'is_active')
                    ->where('is_active', 1)
                    ->orderBy('is_primary', 'desc')->get();
        /*$langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
                    ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code')
                    ->where('client_languages.client_code', Auth::user()->code)
                    ->orderBy('client_languages.is_primary', 'desc')->get();*/

        $submitUrl = route('addon.update', $id);
        //dd($addon->toArray());

        $returnHTML = view('backend.vendor.edit-addon')->with(['languages' => $langs, 'addon' => $addon])->render();
        return response()->json(array('success' => true, 'min_select'=>$addon->min_select, 'max_select'=>$addon->max_select, 'html'=>$returnHTML, 'submitUrl' => $submitUrl));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AddonOption  $addonOption
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    {
        $count = count($request->price);
        $min = ($request->min_select < 1) ? 0 : $request->min_select;
        $max = ($request->max_select < 1) ? 1 : $request->max_select;
        $min = ($min > $count) ? $count : $min;
        $max = ($max > $count) ? $count : $max;
        $max = ($max < $min) ? $min : $max;
        $addon = AddonSet::where('id', $id)->firstOrFail();
        $addon->title = $request->title[0];
        $addon->min_select = $min;
        $addon->max_select = $max;
        $addon->save();

        foreach ($request->language_id as $key => $value) {

            $varTrans = AddonSetTranslation::where('language_id', $value)->where('addon_id', $addon->id)->first();
            if(!$varTrans){
                $varTrans = new AddonSetTranslation();
                $varTrans->addon_id = $addon->id;
                $varTrans->language_id = $value;
            }
            $varTrans->title = $request->title[$key];
            $varTrans->save();
        }

        $exist_options = array();
        foreach ($request->option_id as $key => $value) {

            $curLangId = $request->language_id[0];

            if(empty($value)){
                $varOpt = new AddonOption();
                $varOpt->title = $request->opt_value[$curLangId][$key];
                $varOpt->addon_id = $addon->id;
                $varOpt->price = $request->price[$key];
                $varOpt->save();
                $exist_options[$key] = $varOpt->id;

            } else {
                $varOpt = AddonOption::where('id', $value)->first();
                $varOpt->title = $request->opt_value[$curLangId][$key];
                $varOpt->price = $request->price[$key];
                $varOpt->save();
                $exist_options[$key] = $value;
            }
        }

        foreach($request->opt_id as $lid => $options) {

            foreach($options as $key => $value) {

                if(!empty($value)){
                    $setOptTrans = AddonOptionTranslation::where('language_id', $lid)->where('addon_opt_id', $value)->first();
                    if(!$setOptTrans){
                        $setOptTrans = new AddonOptionTranslation();
                        $setOptTrans->addon_opt_id =$exist_options[$key];
                        $setOptTrans->language_id = $lid;
                    }
                    $setOptTrans->title = $request->opt_value[$lid][$key];
                    $setOptTrans->save();

                }else{
                    $setOptTrans = new AddonOptionTranslation();
                    $setOptTrans->addon_opt_id =$exist_options[$key];
                    $setOptTrans->language_id = $lid;
                    $setOptTrans->title = $request->opt_value[$lid][$key];
                    $setOptTrans->save();
                }
            }
        }
        return redirect()->back()->with('success', 'Addon set updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AddonOption  $addonOption
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id){
        $aos = AddonSet::where('id', $id)->first();
        $aos->status = 2;
        $aos->save();
        return redirect()->back()->with('success', 'Addon set deleted successfully!');
    }
}
