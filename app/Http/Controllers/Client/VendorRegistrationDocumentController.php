<?php

namespace App\Http\Controllers\Client;
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Models\VendorRegistrationDocument;
use App\Http\Controllers\Client\BaseController;
use App\Models\VendorRegistrationDocumentTranslation;

class VendorRegistrationDocumentController extends BaseController{
    use ApiResponser;
    public function store(Request $request){
        try {
            $this->validate($request, [
              'name.0' => 'required|string|max:60',
              'file_type' => 'required',
            ],['name.0' => 'The default language name field is required.']);
            DB::beginTransaction();
            $vendor_registration_document = new VendorRegistrationDocument();
            $vendor_registration_document->file_type = $request->file_type;
            $vendor_registration_document->save();
            $language_id = $request->language_id;
            foreach ($request->name as $k => $name) {
                if($name){
                    $VendorRegistrationDocumentTranslation = new VendorRegistrationDocumentTranslation();
                    $VendorRegistrationDocumentTranslation->name = $name;
                    $VendorRegistrationDocumentTranslation->slug = Str::slug($name, '-');
                    $VendorRegistrationDocumentTranslation->language_id = $language_id[$k];
                    $VendorRegistrationDocumentTranslation->vendor_registration_document_id = $vendor_registration_document->id;
                    $VendorRegistrationDocumentTranslation->save();
                }
            }
            DB::commit();
            return $this->successResponse($vendor_registration_document, 'Vendor Registration Document Added Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request){
        try {
            $vendor_registration_document = VendorRegistrationDocument::with(['translations'])->where(['id' => $request->vendor_registration_document_id])->firstOrFail();
            return $this->successResponse($vendor_registration_document, '');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VendorRegistrationDocument $vendorRegistrationDocument){
         try {
            $this->validate($request, [
              'name.0' => 'required|string|max:60',
              'file_type' => 'required',
            ],['name.0' => 'The default language name field is required.']);
            DB::beginTransaction();
            $vendor_registration_document_id = $request->vendor_registration_document_id;
            $vendor_registration_document = VendorRegistrationDocument::where('id', $vendor_registration_document_id)->first();
            $vendor_registration_document->file_type = $request->file_type;
            $vendor_registration_document->save();
            $language_id = $request->language_id;
            VendorRegistrationDocumentTranslation::where('vendor_registration_document_id', $vendor_registration_document_id)->delete();
            foreach ($request->name as $k => $name) {
                if($name){
                    $VendorRegistrationDocumentTranslation = new VendorRegistrationDocumentTranslation();
                    $VendorRegistrationDocumentTranslation->name = $name;
                    $VendorRegistrationDocumentTranslation->slug = Str::slug($name, '-');
                    $VendorRegistrationDocumentTranslation->language_id = $language_id[$k];
                    $VendorRegistrationDocumentTranslation->vendor_registration_document_id = $vendor_registration_document->id;
                    $VendorRegistrationDocumentTranslation->save();
                }
            }
            DB::commit();
            return $this->successResponse($vendor_registration_document, 'Vendor Registration Document Updated Successfully.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){
        try {
            VendorRegistrationDocument::where('id', $request->vendor_registration_document_id)->delete();
            VendorRegistrationDocumentTranslation::where('vendor_registration_document_id', $request->vendor_registration_document_id)->delete();
            return $this->successResponse([], 'Vendor Registration Document Deleted Successfully.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }
}
