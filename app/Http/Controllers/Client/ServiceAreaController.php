<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{VendorSlotDate, Vendor, VendorSlot, SlotDay, ServiceArea};

class ServiceAreaController extends BaseController{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = '', $vendor_id){
        $rules = array(
            'name' => 'required',
            'latlongs' => 'required'
        );
        $messages = array(
            'name.required' => 'Area name is required',
            'latlongs.required' => 'Service area is required',
        );
        $validation  = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $vendor = Vendor::where('id', $vendor_id)->firstOrFail();
        $area = new ServiceArea();
        if($request->has('area_id')){
            $area = ServiceArea::where('id', $request->area_id)->first();
        }
        $latlng = str_replace('),(', ';', $request->latlongs);
        $latlng = str_replace(')', '', $latlng);
        $latlng = str_replace('(', '', $latlng);
        $latlng = str_replace(', ', ' ', $latlng);
        $codsArray = explode(';', $latlng);
        $latlng = implode(', ', $codsArray);
        $area->vendor_id        = $vendor->id;
        $latlng = $latlng. ', ' . $codsArray[0];
        $area->name             = $request->name;
        $area->geo_array        = $request->latlongs;
        $area->zoom_level       = $request->zoom_level;
        $area->description      = $request->description;
        $area->polygon          = \DB::raw("ST_GEOMFROMTEXT('POLYGON((".$latlng."))')");
        $area->save();
        return redirect()->back()->with('success', 'Service area saved successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceArea  $serviceArea
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $domain = '', $vendor_id){
        $area = ServiceArea::where('id', $request->data)->where('vendor_id', $vendor_id)->first();
        $returnHTML = view('backend.vendor.editArea')->with(['area' => $area])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML, 'zoomLevel'  => $area->zoom_level, 'coordinate'  => $area->geo_array));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceArea  $serviceArea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id){
        $rules = array(
            'name' => 'required',
            'latlongs_edit' => 'required'
        );
        $messages = array(
            'name.required' => 'Area name is required',
            'latlongs_edit.required' => 'Service area is required',
        );
        $validation  = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $area = ServiceArea::where('id', $id)->where('vendor_id', $request->ven_id)->firstOrFail();
        $latlng = str_replace('),(', ';', $request->latlongs_edit);
        $latlng = str_replace(')', '', $latlng);
        $latlng = str_replace('(', '', $latlng);
        $latlng = str_replace(', ', ' ', $latlng);
        $codsArray = explode(';', $latlng);
        $latlng = implode(', ', $codsArray);
        $latlng = $latlng. ', ' . $codsArray[0];
        $area->name           = $request->name;
        $area->description    = $request->description;
        $area->geo_array      = $request->latlongs_edit;
        $area->zoom_level     = $request->zoom_level_edit;
        $area->polygon        = \DB::raw("ST_GEOMFROMTEXT('POLYGON((".$latlng."))')");
        $area->save();
        return redirect()->back()->with('success', 'Service area updated successfully!');
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceArea  $serviceArea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $domain = '', $vendor_id){
        $vendor = Vendor::where('id', $vendor_id)->firstOrFail();
        $area = ServiceArea::where('id', $request->area_id)->delete();
        return redirect()->back()->with('success', 'Service area deleted successfully!');
    }
}
