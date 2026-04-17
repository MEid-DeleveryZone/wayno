<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Promocode;
use App\Models\CartCoupon;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use App\Models\PromoCodeDetail;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PromoCodeController extends Controller{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postPromoCodeList(Request $request){
        try {
            $promo_codes = new \Illuminate\Database\Eloquent\Collection;
            $vendor_id = $request->vendor_id;
            $validator = $this->validatePromoCodeList();
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $vendor = Vendor::where('id', $request->vendor_id)->first();
            if(!$vendor){
                return response()->json(['error' => __('Invalid vendor id.')], 404);
            }
            $now = Carbon::now()->toDateTimeString();
            $product_ids = Product::where('vendor_id', $request->vendor_id)->pluck("id");
            $cart_products = CartProduct::with(['product.variant' => function($q){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        }])->where('vendor_id', $request->vendor_id)->where('cart_id', $request->cart_id)->get();
            $total_minimum_spend = 0;
            foreach ($cart_products as $cart_product) {
                $total_price = 0;
                if(isset($cart_product->product->variant) && !empty($cart_product->product->variant->first()))
                {
                    $total_price = $cart_product->product->variant->first()->price ?? 0;
                }

                $total_minimum_spend += $total_price * $cart_product->quantity;
            }
            if($product_ids){
                $promo_code_details = PromoCodeDetail::whereIn('refrence_id', $product_ids->toArray())->pluck('promocode_id');
                if($promo_code_details->count() > 0){
                    $result1 = Promocode::whereIn('id', $promo_code_details->toArray())->whereDate('expiry_date', '>=', $now)->where('restriction_on', 0)->where('restriction_type', 0)->where('is_deleted', 0)->where('minimum_spend','<=',$total_minimum_spend)->where('maximum_spend','>=',$total_minimum_spend)->get();
                    $promo_codes = $promo_codes->merge($result1);
                }
                $vendor_promo_code_details = PromoCodeDetail::whereHas('promocode')->where('refrence_id', $vendor_id)->pluck('promocode_id');
                $result2 = Promocode::whereIn('id', $vendor_promo_code_details->toArray())->where('restriction_on', 1)->whereHas('details', function($q) use($vendor_id){
                    $q->where('refrence_id', $vendor_id);
                })->where('restriction_on', 1)->where('minimum_spend','<=',$total_minimum_spend)->where('maximum_spend','>=',$total_minimum_spend)->where('is_deleted', 0)->whereDate('expiry_date', '>=', $now)->get();
                $promo_codes = $promo_codes->merge($result2);
            }
            return $this->successResponse($promo_codes, '', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function validate_promo_code(Request $request){
        try {
            $validator = $this->validatePromoCodeList($request);
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $vendor = Vendor::where('id', $request->vendor_id)->first();
            if(!$vendor){
                return response()->json(['error' => __('Invalid vendor id.')], 404);
            }
            $cart_detail = Cart::where('id', $request->cart_id)->first();
            if(!$cart_detail){
                return $this->errorResponse(__('Invalid Cart Id'), 422);
            }

            $now = Carbon::now()->toDateTimeString();
            $product_ids = Product::where('vendor_id', $request->vendor_id)->pluck("id");
            if($product_ids){
                $promo_code_details = PromoCodeDetail::whereIn('refrence_id', $product_ids->toArray())->pluck('promocode_id');
                    $promo_detail = Promocode::where(['name' => $request->promocode])->whereDate('expiry_date', '>=', $now)->where('restriction_on', 0)->where(function($query) use($promo_code_details){
                        $query->where(function ($query2) use ($promo_code_details) {
                            $query2->where('restriction_type', 1);
                            if (!empty($promo_code_details->toArray())) {
                                $query2->whereNotIn('id', $promo_code_details->toArray());
                            }
                        });
                        $query->orWhere(function($query1) use($promo_code_details){
                            $query1->where('restriction_type' , 0);
                            if (!empty($promo_code_details->toArray())) {
                                $query1->whereIn('id', $promo_code_details->toArray());
                            } else {
                                $query1->where('id', 0);
                            }
                        });
                    })->where('is_deleted', 0)->first();
                if (!$promo_detail) {
                    $vendor_promo_code_details = PromoCodeDetail::whereHas('promocode')->where('refrence_id', $request->vendor_id)->pluck('promocode_id');
                    $promo_detail = Promocode::where(['name' => $request->promocode])->where('restriction_on', 1)->where(function($query) use($vendor_promo_code_details){
                        $query->where(function ($query2) use ($vendor_promo_code_details) {
                            $query2->where('restriction_type', 1);
                            if (!empty($vendor_promo_code_details->toArray())) {
                                $query2->whereNotIn('id', $vendor_promo_code_details->toArray());
                            }
                        });
                        $query->orWhere(function($query1) use($vendor_promo_code_details){
                            $query1->where('restriction_type' , 0);
                            if (!empty($vendor_promo_code_details->toArray())) {
                                $query1->whereIn('id', $vendor_promo_code_details->toArray());
                            } else {
                                $query1->where('id', 0);
                            }
                        });
                    })->where('is_deleted', 0)->whereDate('expiry_date', '>=', $now)->first();
                }
            }

            if(!$promo_detail){
                return $this->errorResponse(__('Invalid Promocode'), 422);
            }
            $cart_coupon_detail = CartCoupon::where('cart_id', $request->cart_id)->where('vendor_id', $request->vendor_id)->where('coupon_id', $promo_detail->id)->first();
            if($cart_coupon_detail){
                return $this->errorResponse(__('Coupon Code already applied.'), 422);
            }
            $cart_coupon_detail2 = CartCoupon::where('cart_id', $request->cart_id)->where('coupon_id', $promo_detail->id)->first();
            if($cart_coupon_detail2){
                return $this->errorResponse(__('Coupon Code already applied other vendor.'), 422);
            }
            $cart_products = CartProduct::with(['product.variant' => function ($q) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            }])->where('vendor_id', $request->vendor_id)->where('cart_id', $request->cart_id)->get();
            $total_minimum_spend = 0;
            foreach ($cart_products as $cart_product) {
                $total_price = 0;
                if (isset($cart_product->product->variant) && !empty($cart_product->product->variant->first())) {
                    $total_price = $cart_product->product->variant->first()->price ?? 0;
                }
                $total_minimum_spend += $total_price * $cart_product->quantity;
            }
            if($total_minimum_spend < $promo_detail->minimum_spend){
                return $this->errorResponse(__('Cart amount is less than required amount'), 422);
            }
            if($total_minimum_spend > $promo_detail->maximum_spend){
                return $this->errorResponse(__('Cart amount is greater than required amount'), 422);
            }
            $cart_coupon = new CartCoupon();
            $cart_coupon->cart_id = $request->cart_id;
            $cart_coupon->vendor_id = $request->vendor_id;
            $cart_coupon->coupon_id = $promo_detail->id;
            $cart_coupon->save();
            return $this->successResponse($promo_detail, __('Promotion Code Used Successfully'), 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function postVerifyPromoCode(Request $request){
        try {
            $validator = $this->validatePromoCode();
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $vendor = Vendor::where('id', $request->vendor_id)->first();
            if(!$vendor){
                return response()->json(['error' => __('Invalid vendor id.')], 404);
            }
            $cart_detail = Cart::where('id', $request->cart_id)->first();
            if(!$cart_detail){
                return $this->errorResponse(__('Invalid Cart Id'), 422);
            }
            $cart_detail = Promocode::where('id', $request->coupon_id)->first();
            if(!$cart_detail){
                return $this->errorResponse(__('Invalid Promocode Id'), 422);
            }
            $cart_coupon_detail = CartCoupon::where('cart_id', $request->cart_id)->where('vendor_id', $request->vendor_id)->where('coupon_id', $request->coupon_id)->first();
            if($cart_coupon_detail){
                return $this->errorResponse(__('Coupon Code already applied.'), 422);
            }
            $cart_coupon_detail2 = CartCoupon::where('cart_id', $request->cart_id)->where('coupon_id', $request->coupon_id)->first();
            if($cart_coupon_detail2){
                return $this->errorResponse(__('Coupon Code already applied other vendor.'), 422);
            }
            $cart_coupon = new CartCoupon();
            $cart_coupon->cart_id = $request->cart_id;
            $cart_coupon->vendor_id = $request->vendor_id;
            $cart_coupon->coupon_id = $request->coupon_id;
            $cart_coupon->save();
            return $this->successResponse($cart_coupon, __('Promotion Code Used Successfully'), 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    public function postRemovePromoCode(Request $request){
        try {
            $validator = $this->validatePromoCode();
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $cart_detail = Cart::where('id', $request->cart_id)->first();
            if(!$cart_detail){
                return $this->errorResponse(__('Invalid Cart Id'), 422);
            }
            $cart_detail = Promocode::where('id', $request->coupon_id)->first();
            if(!$cart_detail){
                return $this->errorResponse(__('Invalid Promocode Id'), 422);
            }
            CartCoupon::where('cart_id', $request->cart_id)->where('coupon_id', $request->coupon_id)->delete();
            return $this->successResponse(null, __('Promotion Code Removed Successfully'), 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function validatePromoCodeList(){
        return Validator::make(request()->all(), [
            'vendor_id' => 'required',
        ]);
    }

    public function validatePromoCode(){
        return Validator::make(request()->all(), [
            'cart_id' => 'required',
            'vendor_id' => 'required',
            'coupon_id' => 'required',
        ]);
    }
}
