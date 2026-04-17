<?php
namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use HttpRequest;
use App\Models\{Order,ProductVariant, User, UserAddress, Vendor};
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as GCLIENT;

trait OrderTrait{

    public function ProductVariantStoke($order_id)
    {

        $order = Order::with(['vendors.products.pvariant'])->find($order_id);
        if( isset($order->vendors )){
            foreach ($order->vendors as $vendor) {
                foreach ($vendor->products as $product) {
                    $ProductVariant = ProductVariant::find($product->variant_id);
                    if ($ProductVariant) {
                        $ProductVariant->quantity  = $ProductVariant->quantity - $product->quantity;
                        $ProductVariant->save();
                    }
                }
            }
        }
        return 1;
    }

    public function getDeliveryFeeDispatcher($vendor_id)
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $customer = User::find(Auth::id());
                $cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
                if ($cus_address) {
                    $tasks = array();
                    $vendor_details = Vendor::find($vendor_id);
                    $location[] = array(
                        'latitude' => $vendor_details->latitude ?? 30.71728880,
                        'longitude' => $vendor_details->longitude ?? 76.80350870
                    );
                    $location[] = array(
                        'latitude' => $cus_address->latitude ?? 30.717288800000,
                        'longitude' => $cus_address->longitude ?? 76.803508700000
                    );
                    $postdata =  ['locations' => $location,'type'=>'ecommerce','team_tag' => $dispatch_domain->client_code.'_'.$vendor_id ?? ''];
                    $client = new GClient([
                        'headers' => [
                            'personaltoken' => $dispatch_domain->delivery_service_key,
                            'shortcode' => $dispatch_domain->delivery_service_key_code,
                            'content-type' => 'application/json'
                        ]
                    ]);
                    $url = $dispatch_domain->delivery_service_key_url;
                    $res = $client->post(
                        $url . '/api/get-delivery-fee',
                        ['form_params' => ($postdata)]
                    );
                    $response = json_decode($res->getBody(), true);
                    if ($response && $response['message'] == 'success') {
                        return $response['total'];
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }
}
