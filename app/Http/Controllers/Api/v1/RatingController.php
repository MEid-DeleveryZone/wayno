<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Config;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\OrderProductRatingRequest;
use App\Models\{Order,OrderProductRating,VendorOrderStatus,OrderProduct,OrderProductRatingFile};
use App\Http\Traits\ApiResponser;
use GuzzleHttp\Client as GCLIENT;
use App\Http\Requests\Web\CheckImageRequest;

class RatingController extends BaseController{
	
    use ApiResponser;
    /**
     * update order product rating

     */
    public function updateProductRating(OrderProductRatingRequest $request){
        try {
            $user = Auth::user();
            $order_deliver = 0;
            $order_details = OrderProduct::where('id',$request->order_vendor_product_id)->whereHas('order',function($q){$q->where('user_id',Auth::id());})->first();
            if($order_details)
            $order_deliver = VendorOrderStatus::where(['order_id' => $request->order_id,'vendor_id' => $order_details->vendor_id,'order_status_option_id' => 6])->count();
            if($order_deliver > 0){
                $ratings = OrderProductRating::updateOrCreate(['order_vendor_product_id' => $request->order_vendor_product_id,
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'user_id' => Auth::id()],['rating' => $request->rating,'review' => $request->review??null]);
                if ($image = $request->file('files')) {
                    foreach ($image as $files) {
                    $file =  substr(md5(microtime()), 0, 15).'_'.$files->getClientOriginalName();
                    $storage = Storage::disk('s3')->put('/review', $files, 'public');
                    $img = new OrderProductRatingFile();
                    $img->order_product_rating_id = $ratings->id;
                    $img->file = $storage;
                    $img->save();
                   
                    }
                }
                $this->updateaverageRating($request->product_id);
                if(isset($request->remove_files) && is_array($request->remove_files))    # send index array of deleted images 
                $removefiles = OrderProductRatingFile::where('order_product_rating_id',$ratings->id)->whereIn('id',$request->remove_files)->delete();

                if(isset($request->rating_for_dispatch) && !empty($request->rating_for_dispatch))
                {
                    $staus = $this->setRatingOnDispatch($request);
                } 
       
            }
            if(isset($ratings)) {
                return $this->successResponse($ratings,'Rating Submitted.');
            }
            return $this->errorResponse('Invalid order', 404);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * ratings details
    */
    public function getProductRating(Request $request){
        try {
            $ratings = '';
            $ratings = OrderProductRating::where('id',$request->id)->with('reviewFiles')->first();
            if(isset($ratings))
            return $this->successResponse($ratings,'Rating Details.');
            return $this->errorResponse('Invalid rating', 404);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

     /**
     * update image

     */
    public function uploadFile(CheckImageRequest $request){
        try {
                  $files_set = [];
                  $folder =$request->folder ??'returns';
                  if ($image = $request->file('images')) {
                       foreach ($image as $key => $files) {
                       $file =  substr(md5(microtime()), 0, 15).'_'.$files->getClientOriginalName();
                       $storage = Storage::disk('s3')->put($folder, $files, 'public');
                       $files_set[$key]['name'] = $storage;
                       $files_set[$key]['ids'] = uniqid();
                       $proxy_url = env('IMG_URL1');
                       $image_path = env('IMG_URL2').'/'.\Storage::disk('s3')->url($storage);
                       $files_set[$key]['img_path'] = $proxy_url.'300/300'.$image_path;
                       }
                   }
                 
                if(isset($files_set)) {
                   return $this->successResponse($files_set,'Files Submitted.');
               }
               return $this->errorResponse('Invalid data', 200);
               
           } catch (Exception $e) {
               return $this->errorResponse($e->getMessage(), 400);
           }
       }

        # set rating at dispatch panel 
    public function setRatingOnDispatch($request)
    {
        try {
            $dispatch_domain = $this->checkIfPickupDeliveryOnCommon();
            if ($dispatch_domain && $dispatch_domain != false) {
                $all_location = array();
                $postdata =  [ 'order_id' => $request->rating_for_dispatch??'',
                                'rating' => $request->rating??'',
                                'review' => $request->review??''];
                $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->pickup_delivery_service_key,'shortcode' => $dispatch_domain->pickup_delivery_service_key_code,'content-type' => 'application/json']]);
                $url = $dispatch_domain->pickup_delivery_service_key_url;                      
                $res = $client->post($url.'/api/update-order-feedback',
                    ['form_params' => ($postdata)]
                );
                
                $response = json_decode($res->getBody(), true); 
                if($response && $response['message'] == 'success'){
                   
                }
            }
        }catch(\Exception $e){
              return $e->getMessage();
        }
    }


}
