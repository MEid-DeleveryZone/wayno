<?php

namespace App\Http\Controllers\Front;

use App\Models\{Cart, Client, ClientPreference, DeliveryCart, DeliveryCartTasks, EmailTemplate, Order, OrderRejectingReason, VendorOrderDispatcherStatus, OrderVendor, OrderVendorProduct, Product, User, UserAddress, VendorOrderStatus, Payment};
use Illuminate\Http\Request;
use App\Http\Requests\DispatchOrderStatusUpdateRequest;
use App\Http\Controllers\Front\FrontController;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Front\OrderController as FrontOrderController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Api\v1\PaymentOptionController;
use GuzzleHttp\Client as GCLIENT;

class DispatcherController extends FrontController
{
    use ApiResponser;


    /******************    ---- order status update from dispatch (Need to dispatcher_status_option_id ) -----   ******************/
    public function dispatchOrderStatusUpdate(DispatchOrderStatusUpdateRequest $request, $domain = '', $web_hook_code)
    {
        try {
            DB::beginTransaction();
            $checkiftokenExist = OrderVendor::where('web_hook_code', $web_hook_code)->first();
            if ($checkiftokenExist) {
                $update = VendorOrderDispatcherStatus::updateOrCreate([
                    'dispatcher_id' => null,
                    'order_id' =>  $checkiftokenExist->order_id,
                    'dispatcher_status_option_id' =>  $request->dispatcher_status_option_id,
                    'vendor_id' =>  $checkiftokenExist->vendor_id
                ]);

                $dispatch_status = $request->dispatcher_status_option_id;

                switch ($dispatch_status) {
                    case 1:
                        $status_option_id = 3;
                        break;
                    case 2:
                        $status_option_id = 2;
                        break;
                    case 3:
                        $status_option_id = 4;
                        break;
                    case 4:
                        $status_option_id = 5;
                        break;
                    case 5:
                        $status_option_id = 6;
                        break;
                    case 6:
                        $status_option_id = 7;
                        break;
                    case 7:
                        $status_option_id = 8;
                        break;
                    case 8:
                        $status_option_id = 9;
                        break;
                    default:
                        $status_option_id = null;
                }
                if (isset($status_option_id) && !empty($status_option_id)) {
                    // Set cancelled_by for dispatcher cancellations
                    if ($status_option_id == 3) {
                        OrderVendor::where('web_hook_code', $web_hook_code)->update(['cancelled_by' => 3]);
                    }

                    $checkif = VendorOrderStatus::where([
                        'order_id' =>  $checkiftokenExist->order_id,
                        'order_status_option_id' =>  $status_option_id,
                        'vendor_id' =>  $checkiftokenExist->vendor_id,
                        'order_vendor_id' =>  $checkiftokenExist->id
                    ])->count();
                    if ($checkif == 0) {
                        $update_vendor = VendorOrderStatus::updateOrCreate([
                            'order_id' =>  $checkiftokenExist->order_id,
                            'order_status_option_id' =>  $status_option_id,
                            'vendor_id' =>  $checkiftokenExist->vendor_id,
                            'order_vendor_id' =>  $checkiftokenExist->id
                        ]);

                        OrderVendor::where('vendor_id', $checkiftokenExist->vendor_id)->where('order_id', $checkiftokenExist->order_id)->update(['order_status_option_id' => $status_option_id]);
                    }
                }



                if (isset($request->dispatch_traking_url) && !empty($request->dispatch_traking_url)) {
                    $update_tr = OrderVendor::where('web_hook_code', $web_hook_code)->update(['dispatch_traking_url' =>  $request->dispatch_traking_url]);
                }
                OrderVendor::where('vendor_id', $checkiftokenExist->vendor_id)->where('order_id', $checkiftokenExist->order_id)->update(['dispatcher_status_option_id' => $request->dispatcher_status_option_id]);

                DB::commit();

                $currentOrderStatus = OrderVendor::where(['vendor_id' => $checkiftokenExist->vendor_id, 'order_id' => $checkiftokenExist->order_id])->first();
                $orderData = Order::find($checkiftokenExist->order_id);
                //send push notification to customers
                sendStatusChangePushNotificationCustomer([$currentOrderStatus->user_id], $orderData, $status_option_id, $checkiftokenExist->vendor_id);

                $message = "Order status updated.";
                return $this->successResponse($update, $message);
            } else {
                DB::rollback();
                $message = "Invalid Order Token";
                return $this->errorResponse($message, 400);
            }
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    /******************    ---- pickup delivery status update (Need to dispatcher_status_option_id ) -----   ******************/
    public function dispatchPickupDeliveryUpdate(Request $request, $domain = '', $web_hook_code)
    {
        try {
            DB::beginTransaction();
            $checkiftokenExist = OrderVendor::where('web_hook_code', $web_hook_code)->first();
            if ($checkiftokenExist) {

                $dispatch_status = $request->dispatcher_status_option_id;

                switch ($dispatch_status) {
                    case 1:
                        $status_option_id = 3;
                        break;
                    case 2:
                        $status_option_id = 2;
                        break;
                    case 3:
                        $status_option_id = 4;
                        break;
                    case 4:
                        $status_option_id = 5;
                        break;
                    case 5:
                        $status_option_id = 6;
                        break;
                    case 6:
                        $status_option_id = 7;
                        break;
                    case 7:
                        $status_option_id = 8;
                        break;
                    case 8:
                        $status_option_id = 9;
                        break;
                    default:
                        $status_option_id = null;
                }
                if (isset($status_option_id) && !empty($status_option_id)) {
                    // Set cancelled_by for dispatcher cancellations
                    if ($status_option_id == 3) {
                        OrderVendor::where('web_hook_code', $web_hook_code)->update(['cancelled_by' => 3]);
                    }

                    /**
                     * if rider is re assigned the status not updating because of this check
                     */
                    // $checkif = VendorOrderStatus::where([
                    //     'order_id' =>  $checkiftokenExist->order_id,
                    //     'order_status_option_id' =>  $status_option_id,
                    //     'vendor_id' =>  $checkiftokenExist->vendor_id,
                    //     'order_vendor_id' =>  $checkiftokenExist->id
                    // ])->count();
                    $checkif = 0;
                    if ($checkif == 0) {
                        // $update_vendor = VendorOrderStatus::updateOrCreate([
                        //     'order_id' =>  $checkiftokenExist->order_id,
                        //     'order_status_option_id' =>  $status_option_id,
                        //     'vendor_id' =>  $checkiftokenExist->vendor_id,
                        //     'order_vendor_id' =>  $checkiftokenExist->id
                        // ]);
                        $update_vendor = VendorOrderStatus::Create([
                            'order_id' =>  $checkiftokenExist->order_id,
                            'order_status_option_id' =>  $status_option_id,
                            'vendor_id' =>  $checkiftokenExist->vendor_id,
                            'order_vendor_id' =>  $checkiftokenExist->id
                        ]);

                        OrderVendor::where('vendor_id', $checkiftokenExist->vendor_id)->where('order_id', $checkiftokenExist->order_id)->update(['order_status_option_id' => $status_option_id]);
                    }
                }

                $update = VendorOrderDispatcherStatus::updateOrCreate([
                    'dispatcher_id' => null,
                    'order_id' =>  $checkiftokenExist->order_id,
                    'dispatcher_status_option_id' =>  $request->dispatcher_status_option_id,
                    'vendor_id' =>  $checkiftokenExist->vendor_id
                ]);

                if (isset($request->dispatch_traking_url) && !empty($request->dispatch_traking_url)) {
                    $update_tr = OrderVendor::where('web_hook_code', $web_hook_code)->update(['dispatch_traking_url' =>  $request->dispatch_traking_url]);
                }
                if (isset($request->cancel_reason) && !empty($request->cancel_reason)) {
                    $order_rejecting_reason = OrderRejectingReason::where('name', $request->cancel_reason)->first();
                    if ($order_rejecting_reason == null) {
                        $order_rejecting_reason = OrderRejectingReason::create(['name' => $request->cancel_reason]);
                    }
                    OrderVendor::where('web_hook_code', $web_hook_code)->update(['reject_reason' =>  $order_rejecting_reason->id]);
                }

                OrderVendor::where('vendor_id', $checkiftokenExist->vendor_id)->where('order_id', $checkiftokenExist->order_id)->update(['dispatcher_status_option_id' => $request->dispatcher_status_option_id]);

                $currentOrderStatus = OrderVendor::where(['vendor_id' => $checkiftokenExist->vendor_id, 'order_id' => $checkiftokenExist->order_id])->first();
                $orderData = Order::find($checkiftokenExist->order_id);
                DB::commit();
                //send push notification to customers
                sendStatusChangePushNotificationCustomer([$currentOrderStatus->user_id], $orderData, $status_option_id, $checkiftokenExist->vendor_id);

                if ($status_option_id == 6) {
                    $this->sendDeliveredOrderMail($checkiftokenExist->order_id);
                }
                if ($status_option_id == 3) {
                    $clientOrder = new ClientOrderController();
                    $clientOrder->sendRejectOrderMail($checkiftokenExist->order_id);
                }
                $message = "Order status updated.";
                return $this->successResponse($update, $message);
            } else {
                DB::rollback();
                $message = "Invalid Order Token";
                return $this->errorResponse($message, 400);
            }
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    /**
     * send order delivered email
     */
    public function sendDeliveredOrderMail($order_id)
    {
        $order = Order::with('vendors')->findOrFail($order_id);
        $user = User::where('id', $order->user_id)->first();
        
        if (!$user) {
            return;
        }
        
        $currSymbol = 'AED';
        $returnHTML = '';
        
        try {
            if ($order->type == 7 || $order->type == 9 || $order->type == 10) {
                $DeliveryCart = DeliveryCart::with('product', 'vendor', 'category')->where('id', $order->delivery_cart_id)->first();
                $location = DeliveryCartTasks::where('delivery_cart_id', $DeliveryCart->id)->get();

                $pick_up = '';
                $drop_off = '';

                // Check if pickup is enabled for this category
                if ($DeliveryCart->category && $DeliveryCart->category->is_pickup_enabled == 1 && isset($location[0])) {
                    $pick_up = $location[0]->building_villa_flat_no . " " . $location[0]->street . " " . $location[0]->area . " " . $location[0]->city . " " . $location[0]->address;
                }

                // Check if dropoff is enabled for this category
                if ($DeliveryCart->category && $DeliveryCart->category->is_dropoff_enabled == 1 && isset($location[1])) {
                    $drop_off = $location[1]->building_villa_flat_no . " " . $location[1]->street . " " . $location[1]->area . " " . $location[1]->city . " " . $location[1]->address;
                }

                $returnHTML = view('email.orderProducts')->with(['order' => $order, 'currencySymbol' => $currSymbol, 'DeliveryCart' => $DeliveryCart, 'pick_up' => $pick_up, 'drop_off' => $drop_off])->render();
            } else {
                if ($user) {
                    $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('user_id', $user->id)->first();
                } else {
                    $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
                }
                if ($cart) {
                    $orderController = new FrontOrderController();
                    $cartData = $orderController->getCart($cart, 0, '1', '1');
                }
                $address = UserAddress::where('id', $order->address_id)->first();
                $addressStr = $address ? ($address->address . ', ' . $address->state . ', ' . $address->country . ', ' . $address->pincode) : '';
                $returnHTML = view('email.orderVendorProducts')->with(['cartData' => $cartData ?? [], 'currencySymbol' => $currSymbol, 'address' => $addressStr])->render();
            }
            
            // Use new EmailService for multi-language and RTL support
            // Note: Template ID 6 is for order delivered email
            $this->sendEmail(
                $user->email,
                6, // Order delivered email template ID
                [
                    'name' => $user->name,
                    'order_no' => $order->order_number,
                    'products' => $returnHTML,
                    'order-delivered-email-banner' => asset("images/email/order-delivered-email-banner.jpg")
                ],
                [],
                $user->language_id ?? null,
                true,
                'forgot_password_email'
            );
        } catch (Exception $th) {
            Log::info("Error From Order Delivered Email: " . $th->getMessage());
        }
    }

    /******************    ---- order details update from dispatch -----   ******************/
    public function updateDispatchOrderDetails(Request $request)
    {
        try {
            DB::beginTransaction();
            $form_params = $request->all();
            $web_hook_code = $form_params['web_hook_code'];
            $checkiftokenExist = OrderVendor::where('web_hook_code', $web_hook_code)->first();
            if ($checkiftokenExist) {
                $order_number = $form_params['orderId'];
                $order_id = Order::where('order_number', $order_number)->value('id');
                $order_price = Order::where('order_number', $order_number)->value('payable_amount');
                $vendor = explode("_", $form_params['vendor_tag']);
                $vendor_id = $vendor[1];
                $product_tag = $form_params['product_tag'];
                $is_sameEmirate = $form_params['is_sameEmirate'];
                $payable_amount = $form_params['payable_amount'];
                $product_id = OrderVendorProduct::where('order_id', $order_id)->value('product_id');

                $product = Product::with('pimage', 'variants', 'taxCategory.taxRate', 'addon')
                    ->where('vendor_id', $vendor_id)
                    ->where('tags', $product_tag)
                    ->first();
                $taxable_amount = 0;
                if ($product->tax_category_id > 0) {
                    foreach ($product->taxCategory->taxRate as $tax_rate_detail) {
                        $rate = round($tax_rate_detail->tax_rate);
                        $product_tax = $payable_amount * $rate / 100;
                        $taxable_amount = $taxable_amount + $product_tax;
                    }
                }
                $order_product_image = '';
                if ($product->pimage) {
                    $order_product_image = $product->pimage->first() ? $product->pimage->first()->path : '';
                }
                $product_sla = $is_sameEmirate ? $product->sla_same_emirates : $product->sla_diff_emirates;


                if ($checkiftokenExist->vendor_id != $vendor_id) {
                    Order::where('id', $order_id)
                        ->update([
                            'total_amount' => $payable_amount,
                            'taxable_amount' => $taxable_amount,
                            'payable_amount' => $payable_amount,
                        ]);
                    OrderVendor::where('order_id', $order_id)
                        ->update([
                            'vendor_id' => $vendor_id,
                            'taxable_amount' => $taxable_amount,
                            'subtotal_amount' => $payable_amount,
                            'payable_amount' => $payable_amount,
                        ]);
                    VendorOrderStatus::where('order_id', $order_id)
                        ->where('order_status_option_id', $checkiftokenExist->order_status_option_id)
                        ->update(['vendor_id' => $vendor_id]);

                    $product_data = [
                        'product_id' => $product->id,
                        'product_name' => $product->title,
                        'image' => $order_product_image,
                        'price' => $payable_amount,
                        'vendor_id' => $vendor_id,
                        'sla' => $product_sla,
                        'dimensions' => $product->dimensions,
                        'weight' => $product->weight_description,
                    ];
                    OrderVendorProduct::where('order_id', $order_id)
                        ->update($product_data);

                    DB::commit();
                    $message = "Order details updated.";
                    return $this->successResponse($product_data, $message);
                } elseif ($product_id != $product->id) {
                    Order::where('id', $order_id)
                        ->update([
                            'total_amount' => $payable_amount,
                            'taxable_amount' => $taxable_amount,
                            'payable_amount' => $payable_amount,
                        ]);
                    OrderVendor::where('order_id', $order_id)
                        ->update([
                            'taxable_amount' => $taxable_amount,
                            'subtotal_amount' => $payable_amount,
                            'payable_amount' => $payable_amount,
                        ]);
                    $product_data = [
                        'product_id' => $product->id,
                        'product_name' => $product->title,
                        'image' => $order_product_image,
                        'price' => $payable_amount,
                        'vendor_id' => $vendor_id,
                        'sla' => $product_sla,
                        'dimensions' => $product->dimensions,
                        'weight' => $product->weight_description,
                    ];
                    OrderVendorProduct::where('order_id', $order_id)
                        ->update($product_data);

                    DB::commit();
                    $message = "Order details updated.";
                    return $this->successResponse($product_data, $message);
                } elseif ($order_price != $payable_amount) {
                    Order::where('id', $order_id)
                        ->update([
                            'total_amount' => $payable_amount,
                            'taxable_amount' => $taxable_amount,
                            'payable_amount' => $payable_amount,
                        ]);
                    OrderVendor::where('order_id', $order_id)
                        ->update([
                            'taxable_amount' => $taxable_amount,
                            'subtotal_amount' => $payable_amount,
                            'payable_amount' => $payable_amount,
                        ]);
                    $product_data = [
                        'price' => $payable_amount,
                        'product_name' => $product->title,
                        'weight' => $product->weight_description,
                        'sla' => $product_sla,
                        'dimensions' => $product->dimensions,
                    ];
                    OrderVendorProduct::where('order_id', $order_id)
                        ->update($product_data);

                    DB::commit();
                    $message = "Order details updated.";
                    return $this->successResponse($product_data, $message);
                } else {
                    DB::rollback();
                    $message = "No changes";
                    return $this->errorResponse($message, 400);
                }
            } else {
                DB::rollback();
                $message = "Invalid Order Token";
                return $this->errorResponse($message, 400);
            }
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse('Error', 400);
        }
    }

    public function dispatchOrderRefundUpdate(Request $request)
    {
        try {
            //  Check if webhook code exists
            $webhook = $request->webhook;
            $orderVendor = OrderVendor::where('web_hook_code', $webhook)->first();
            if (!$orderVendor) {
                return response()->json(['error' => 'Invalid webhook code'], 404);
            }

            // Fetch order details
            $order = Order::with(['paymentOption'])
                ->where('id', $orderVendor->order_id)
                ->first();
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }
            //  Validate refund amount
            if (!$request->has('refund_amount') || !is_numeric($request->refund_amount)) {
                return response()->json(['error' => 'Refund amount is required and must be numeric'], 400);
            }
            $refundAmount = (float) $request->refund_amount;
            //  Process payment refund if applicable
            if (in_array($order->paymentOption->code, ['stripe', 'applepay'])) {
                $payment = Payment::where('order_id', $order->id)->first();
                if ($payment) {
                    $response = PaymentOptionController::StripeRefund(
                        $payment->transaction_id,
                        $refundAmount
                    );
                    if ($response['status'] == 'success') {
                        $orderController = new OrderController();
                        $dispatch_domain = $orderController->getDispatchDomain();
                        if ($dispatch_domain && $dispatch_domain != false) {
                            $postdata =  [
                                'order_number'  => $order->order_number,
                                'is_refund'     => 1,
                                'refund_amount' => $request->refund_amount
                            ];
                            $client = new GCLIENT([
                                'headers' => [
                                    'personaltoken' => $dispatch_domain->delivery_service_key,
                                    'shortcode'     => $dispatch_domain->delivery_service_key_code,
                                    'content-type'  => 'application/json'
                                ]
                            ]);

                            $url = $dispatch_domain->delivery_service_key_url;
                            $res = $client->post(
                                $url . '/api/refund-status-update',
                                ['form_params' => ($postdata)]
                            );
                        }
                        return response()->json(['status' => 'success', 'message' => $response['message']], 200);
                    } else {
                        $orderController = new OrderController();
                        $dispatch_domain = $orderController->getDispatchDomain();
                        if ($dispatch_domain && $dispatch_domain != false) {
                            $postdata =  [
                                'order_number'  => $orderVendor->order_id,
                                'is_refund'     => 2,
                                'refund_amount' => $request->refund_amount
                            ];
                            $client = new GCLIENT([
                                'headers' => [
                                    'personaltoken' => $dispatch_domain->delivery_service_key,
                                    'shortcode'     => $dispatch_domain->delivery_service_key_code,
                                    'content-type'  => 'application/json'
                                ]
                            ]);

                            $url = $dispatch_domain->delivery_service_key_url;
                            $res = $client->post(
                                $url . '/api/refund-status-update',
                                ['form_params' => ($postdata)]
                            );
                        }
                        return response()->json(['status' => 'error', 'message' => $response['message']], 400);
                    }
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Payment record not found, refund not initiated.'], 400);
                }
            }
            return response()->json(['status' => 'error', 'message' => 'No applicable refund process for this payment method'], 400);
        } catch (\Exception $e) {
            Log::error('Refund Webhook Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }
}
