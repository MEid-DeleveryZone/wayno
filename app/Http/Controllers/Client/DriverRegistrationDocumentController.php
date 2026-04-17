<?php

namespace App\Http\Controllers\Client;
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Models\DriverRegistrationDocument;
use App\Http\Controllers\Client\BaseController;
use App\Models\DriverRegistrationDocumentTranslation;

class DriverRegistrationDocumentController extends BaseController{
    use ApiResponser;
    public function store(Request $request){
        try {
            $this->validate($request, [
              'name.0' => 'required|string|max:60',
              'file_type' => 'required',
            ],['name.0' => 'The default language name field is required.']);
            DB::beginTransaction();
            $driver_registration_document = new DriverRegistrationDocument();
            $driver_registration_document->file_type = $request->file_type;
            $driver_registration_document->save();
            $language_id = $request->language_id;
            foreach ($request->name as $k => $name) {
                if($name){
                    $DriverRegistrationDocumentTranslation = new DriverRegistrationDocumentTranslation();
                    $DriverRegistrationDocumentTranslation->name = $name;
                    $DriverRegistrationDocumentTranslation->slug = Str::slug($name, '-');
                    $DriverRegistrationDocumentTranslation->language_id = $language_id[$k];
                    $DriverRegistrationDocumentTranslation->driver_registration_document_id = $driver_registration_document->id;
                    $DriverRegistrationDocumentTranslation->save();
                }
            }
            DB::commit();
            return $this->successResponse($driver_registration_document, 'Driver Registration Document Added Successfully.');
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
            $driver_registration_document = DriverRegistrationDocument::with(['translations'])->where(['id' => $request->driver_registration_document_id])->firstOrFail();
            return $this->successResponse($driver_registration_document, '');
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
    public function update(Request $request, DriverRegistrationDocument $driverRegistrationDocument){
         try {
            $this->validate($request, [
              'name.0' => 'required|string|max:60',
              'file_type' => 'required',
            ],['name.0' => 'The default language name field is required.']);
            DB::beginTransaction();
            $driver_registration_document_id = $request->driver_registration_document_id;
            $driver_registration_document = DriverRegistrationDocument::where('id', $driver_registration_document_id)->first();
            $driver_registration_document->file_type = $request->file_type;
            $driver_registration_document->save();
            $language_id = $request->language_id;
            DriverRegistrationDocumentTranslation::where('driver_registration_document_id', $driver_registration_document_id)->delete();
            foreach ($request->name as $k => $name) {
                if($name){
                    $DriverRegistrationDocumentTranslation = new DriverRegistrationDocumentTranslation();
                    $DriverRegistrationDocumentTranslation->name = $name;
                    $DriverRegistrationDocumentTranslation->slug = Str::slug($name, '-');
                    $DriverRegistrationDocumentTranslation->language_id = $language_id[$k];
                    $DriverRegistrationDocumentTranslation->driver_registration_document_id = $driver_registration_document->id;
                    $DriverRegistrationDocumentTranslation->save();
                }
            }
            DB::commit();
            return $this->successResponse($driver_registration_document, 'Driver Registration Document Updated Successfully.');
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
            DriverRegistrationDocument::where('id', $request->driver_registration_document_id)->delete();
            DriverRegistrationDocumentTranslation::where('driver_registration_document_id', $request->driver_registration_document_id)->delete();
            return $this->successResponse([], 'Driver Registration Document Deleted Successfully.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage());
        }
    }
}
