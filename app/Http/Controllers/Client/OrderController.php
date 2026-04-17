<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Api\v1\PaymentOptionController;
use App\Http\Controllers\Front\OrderController as FrontOrderController;
use Illuminate\Support\Facades\Session;
use App\Models\Tax;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Cart, ClientPreference, NotificationTemplate, OrderProduct, OrderVendor, UserAddress, Vendor, OrderReturnRequest, UserDevice, UserVendor, LuxuryOption, ClientCurrency, DeliveryCart, DeliveryCartTasks, EmailTemplate, OrderRejectingReason, Payment, VendorOrderStatus, VendorOrderDispatcherStatus, OrderStatusOption};
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Models\Client as CP;
use App\Models\Transaction;
use App\Models\AutoRejectOrderCron;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\OrderTrait;
use App\Services\FirebaseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends BaseController
{

    use ApiResponser;
    use OrderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        // $orders = Order::with(['vendors.products','orderStatusVendor', 'address','user'])->orderBy('id', 'DESC');
        // if (Auth::user()->is_superadmin == 0) {
        //     $orders = $orders->whereHas('vendors.vendor.permissionToUser', function ($query) {
        //         $query->where('user_id', Auth::user()->id);
        //     });
        // }
        // $orders = $orders->get();
        // foreach ($orders as $order) {
        //     $order->address = $order->address ? $order->address['address'] : '';
        //     $order->created_date = convertDateTimeInTimeZone($order->created_at, $user->timezone, 'd-m-Y, H:i A');
        //     foreach ($order->vendors as $vendor) {
        //         $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
        //         $vendor->order_status = $vendor_order_status ? $vendor_order_status->OrderStatusOption->title : '';
        //         foreach ($vendor->products as $product) {
        //             $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
        //         }
        //     }
        // }
        $return_requests = OrderReturnRequest::where('status', 'Pending');
        if (Auth::user()->is_superadmin == 0) {
            $return_requests = $return_requests->whereHas('order.vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $return_requests = $return_requests->count();
        // Pending counts
        $pending_order_count = Order::with('vendors')->whereHas('vendors', function ($query) {
            $query->where('order_status_option_id', 1);
        });
        if (Auth::user()->is_superadmin == 0) {
            $pending_order_count = $pending_order_count->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $pending_order_count = $pending_order_count->where(function ($q1) {
            $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
            $q1->orWhere(function ($q2) {
                $q2->where('payment_option_id', 1);
            });
        })->count();

        // past orders count
        $past_order_count = Order::with('vendors')->whereHas('vendors', function ($query) {
            $query->whereIn('order_status_option_id', [6, 3]);
        });
        if (Auth::user()->is_superadmin == 0) {
            $past_order_count = $past_order_count->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $past_order_count = $past_order_count->where(function ($q1) {
            $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
            $q1->orWhere(function ($q2) {
                $q2->where('payment_option_id', 1);
            });
        })->count();

        // active orders count
        $active_order_count = Order::with('vendors')->whereHas('vendors', function ($query) {
            $query->whereIn('order_status_option_id', [2, 4, 5]);
        });
        if (Auth::user()->is_superadmin == 0) {
            $active_order_count = $active_order_count->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $active_order_count = $active_order_count->where(function ($q1) {
            $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
            $q1->orWhere(function ($q2) {
                $q2->where('payment_option_id', 1);
            });
        })->count();
        $clientCurrency = ClientCurrency::where('is_primary', 1)->with('currency')->first();
        if (isset($clientCurrency->currency) && $clientCurrency->currency->iso_code == "AED") {
            $CurremcySymbel = $clientCurrency->currency->iso_code;
        } else {
            $CurremcySymbel = $clientCurrency->currency->symbol;
        }
        $rejecting_reasons = OrderRejectingReason::where('type', 0)->get();
        return view('backend.order.index', compact('return_requests', 'pending_order_count', 'active_order_count', 'past_order_count', 'CurremcySymbel', 'rejecting_reasons'));
    }

    public function postOrderFilter(Request $request, $domain = '')
    {
        $user = Auth::user();
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $filter_order_status = $request->filter_order_status;
        $orders = Order::with(['vendors.products', 'vendors.status', 'orderStatusVendor', 'address', 'user'])->orderBy('id', 'DESC');
        if (Auth::user()->is_superadmin == 0) {
            $orders = $orders->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        if (!empty($request->search_keyword)) {
            $orders = $orders->where('order_number', 'like', '%' . $request->search_keyword . '%');
        }

        $order_count = Order::with('vendors')->where(function ($q1) {
            $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
            $q1->orWhere(function ($q2) {
                $q2->where('payment_option_id', 1);
            });
        })->orderBy('id', 'asc');
        if (Auth::user()->is_superadmin == 0) {
            $order_count = $order_count->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $pending_orders = clone $order_count;
        $active_orders  = clone $order_count;
        $orders_history = clone $order_count;

        if ($filter_order_status) {
            switch ($filter_order_status) {
                case 'pending_orders':
                    $orders = $orders->whereHas('vendors', function ($query) {
                        $query->where('order_status_option_id', 1);
                    });

                    break;
                case 'active_orders':
                    $order_status_options = [2, 4, 5, 7, 8, 9];
                    $orders = $orders->whereHas('vendors', function ($query) use ($order_status_options) {
                        $query->whereIn('order_status_option_id', $order_status_options);
                    });

                    break;
                case 'orders_history':
                    $order_status_options = [6, 3];
                    $orders = $orders->whereHas('vendors', function ($query) use ($order_status_options) {
                        $query->whereIn('order_status_option_id', $order_status_options);
                    });

                    break;
            }
        }
        $orders = $orders->whereHas('vendors')->where(function ($q1) {
            $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
            $q1->orWhere(function ($q2) {
                $q2->where('payment_option_id', 1);
            });
        })->select('*', 'id as total_discount_calculate')->paginate(30);


        $pending_orders = $pending_orders->whereHas('vendors', function ($query) {
            $query->where('order_status_option_id', 1);
        })->count();

        $order_status_optionsa = [2, 4, 5, 7, 8, 9];
        $active_orders = $active_orders->whereHas('vendors', function ($query) {
            $query->whereIn('order_status_option_id', [2, 4, 5, 7, 8, 9]);
        })->count();

        $order_status_optionsd = [6, 3];
        $orders_history = $orders_history->whereHas('vendors', function ($query) {
            $query->whereIn('order_status_option_id', [6, 3]);
        })->count();

        foreach ($orders as $key => $order) {
            $product_sla = '';
            $order->created_date = convertDateTimeInTimeZone($order->created_at, $user->timezone, 'd-m-Y, h:i A');
            $order->scheduled_date_time = !empty($order->scheduled_date_time) ? convertDateTimeInTimeZone($order->scheduled_date_time, $user->timezone, 'M d, Y h:i A') : '';
            foreach ($order->vendors as $vendor) {
                $vendor->vendor_detail_url = route('order.show.detail', [$order->id, $vendor->vendor_id]);
                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                $vendor->order_status = $vendor_order_status ? $vendor_order_status->OrderStatusOption->title : '';
                $vendor->order_vendor_id = $vendor_order_status ? $vendor_order_status->order_vendor_id : '';
                $vendor->vendor_name = $vendor ? $vendor->vendor->name : '';
                
                // Ensure web_hook_code and dispatch_traking_url are available for order type detection
                if (!isset($vendor->web_hook_code)) {
                    $vendor->web_hook_code = $vendor->getAttribute('web_hook_code');
                }
                if (!isset($vendor->dispatch_traking_url)) {
                    $vendor->dispatch_traking_url = $vendor->getAttribute('dispatch_traking_url');
                }
                
                $product_total_count = 0;
                foreach ($vendor->products as $product) {
                    $product_total_count += $product->quantity * $product->price;
                    $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
                    $product_sla  = $product->sla ? $product->sla : '';
                }
                $vendor->product_total_count = $product_total_count;
                $vendor->final_amount = $vendor->taxable_amount + $product_total_count;
            }
            $luxury_option_name = '';
            if ($order->luxury_option_id > 0) {
                $luxury_option = LuxuryOption::where('id', $order->luxury_option_id)->first();
                if ($luxury_option->title == 'takeaway') {
                    $luxury_option_name = getNomenclatureName('Takeaway', $langId, false);
                } elseif ($luxury_option->title == 'dine_in') {
                    $luxury_option_name = 'Dine-In';
                } else {
                    $luxury_option_name = 'Delivery';
                }
            }
            $order->luxury_option_name = $luxury_option_name;
            $order->product_sla = $product_sla;
            if ($order->vendors->count() == 0) {
                $orders->forget($key);
            }
        }

        return $this->successResponse(['orders' => $orders, 'pending_orders' => $pending_orders, 'active_orders' => $active_orders, 'orders_history' => $orders_history], '', 201);
    }
    /**
     * Display the order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */

    public function getOrderDetail($domain = '', $order_id, $vendor_id)
    {
        $vendor_order_status_option_ids = [];
        $vendor_order_status_created_dates = [];
        $order = Order::with(array(
            'vendors' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            },
            'vendors.products.prescription' => function ($query) use ($vendor_id, $order_id) {
                $query->where('vendor_id', $vendor_id)->where('order_id', $order_id);
            },
            'vendors.products' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            },
            'vendors.rejectingReason'
        ))->findOrFail($order_id);
        foreach ($order->vendors as $key => $vendor) {
            $vendor->reject_reason = $vendor->rejectingReason->name ?? '';
            foreach ($vendor->products as $key => $product) {
                $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
            }
        }


        // Get enhanced tracking data
        $trackingData = $this->getEnhancedTrackingData($order);
        
        $clientCurrency = ClientCurrency::where('is_primary', 1)->with('currency')->first();
        if (isset($clientCurrency->currency) && $clientCurrency->currency->iso_code == "AED") {
            $CurremcySymbel = $clientCurrency->currency->iso_code;
        } else {
            $CurremcySymbel = $clientCurrency->currency->symbol;
        }
        return view('backend.order.view')->with([
            'vendor_id' => $vendor_id,
            'order' => $order,
            'CurremcySymbel' => $CurremcySymbel,
            'trackingData' => $trackingData
        ]);
    }

    /**
     * Get enhanced tracking data for admin panel
     */
    private function getEnhancedTrackingData($order)
    {
        $trackingData = [
            'timeline_data' => []
        ];

        try {
            // Get timeline data
            $trackingData['timeline_data'] = $this->getOrderTimeline($order);

        } catch (Exception $e) {
            Log::error('Error getting timeline data: ' . $e->getMessage());
        }

        return $trackingData;
    }



    /**
     * Get order timeline for admin panel
     */
    private function getOrderTimeline($order)
    {
        $timeline = [];
        
        try {
            // Get all status changes for this order
            $statusHistory = $order->vendors->flatMap(function ($vendor) {
                return $vendor->allStatus;
            })->sortBy('created_at');

            // Get current status
            $currentStatus = $statusHistory->sortByDesc('created_at')->first();
            $currentStatusId = $currentStatus ? $currentStatus->order_status_option_id : null;

            // Get all order status options ordered by sort_order
            $orderStatusesQuery = \App\Models\OrderStatusOption::select('id', 'title', 'image', 'sort_order', 'description')
                ->where('type', 1) // Only order statuses, not dispatch statuses
                ->orderBy('sort_order');

            // If cancelled, only show Placed and Rejected
            if ($currentStatusId == 3) {
                $orderStatuses = $orderStatusesQuery->whereIn('id', [1, 3])->get();
            } else {
                // For all other orders, exclude Rejected
                $orderStatuses = $orderStatusesQuery->where('id', '!=', 3)->get();
            }

            // Icon mapping for different status types
            $iconMapping = [
                'placed' => '/images/status/rider_accepted.png',
                'accepted' => '/images/status/rider_accepted.png',
                'processing' => '/images/status/rider_accepted.png',
                'out for delivery' => '/images/status/out_for_delivery.png',
                'delivered' => '/images/status/request_completed.png',
                'completed' => '/images/status/request_completed.png',
                'cancelled' => '/images/status/rider_reached.png',
                'rejected' => '/images/status/rider_reached.png',
                'picked' => '/images/status/rider_picked.png',
                'arrived' => '/images/status/rider_arrived.png',
                'reached' => '/images/status/rider_reached.png'
            ];

            // Build timeline based on sort_order
            foreach ($orderStatuses as $statusOption) {
                $statusRecord = $statusHistory->where('order_status_option_id', $statusOption->id)->first();
                
                // Determine icon based on status title
                $statusTitle = strtolower($statusOption->title);
                $icon = '/assets/images/order_icon.svg'; // Default icon
                
                foreach ($iconMapping as $keyword => $iconPath) {
                    if (strpos($statusTitle, $keyword) !== false) {
                        $icon = $iconPath;
                        break;
                    }
                }
                
                // Use custom image if available in database
                if ($statusOption->image) {
                    $icon = $statusOption->image;
                }
                
                // Check if this status is completed or current
                $isCompleted = $statusRecord && $statusRecord->created_at;
                $isCurrent = $statusOption->id == $currentStatusId;
                
                // Determine if this status should be shown as completed
                $shouldShowAsCompleted = false;
                if ($isCompleted) {
                    $shouldShowAsCompleted = true;
                } elseif ($currentStatusId && $currentStatusId != 3) {
                    // For non-rejected orders, show statuses before current as completed
                    if ($statusOption->id < $currentStatusId) {
                        $shouldShowAsCompleted = true;
                    }
                }
                
                // Determine CSS class
                $cssClass = '';
                if ($isCurrent) {
                    $cssClass = $currentStatusId == 3 ? 'cancelled' : 'green';
                } elseif ($shouldShowAsCompleted) {
                    $cssClass = $statusOption->id == 3 ? 'cancelled' : 'blue';
                }
                
                $timeline[] = [
                    'status' => __($statusOption->title),
                    'date' => $statusRecord ? $statusRecord->created_at->format('Y-m-d H:i:s') : null,
                    'icon' => $icon,
                    'class' => $cssClass,
                    'is_current' => $isCurrent,
                    'is_completed' => $shouldShowAsCompleted,
                    'description' => __($statusOption->description ?? '')
                ];
            }

            // If no timeline data, return basic order placed status
            if (empty($timeline)) {
                return [
                    [
                        'status' => __('Order placed'),
                        'date' => $order->created_at->format('Y-m-d H:i:s'),
                        'icon' => '/images/status/rider_accepted.png',
                        'class' => 'green',
                        'is_current' => true,
                        'is_completed' => true,
                        'description' => __('Your order has been placed successfully')
                    ]
                ];
            }

        } catch (Exception $e) {
            Log::error('Error getting order timeline: ' . $e->getMessage());
        }

        return $timeline;
    }
    /**
     * Change the status of order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $client_preferences = ClientPreference::first();
        try {
            $timezone                   = Auth::user()->timezone;
            $vendor_order_status_check  = VendorOrderStatus::where('order_id', $request->order_id)->where('vendor_id', $request->vendor_id)->where('order_status_option_id', $request->status_option_id)->first();
            $currentOrderStatus         = OrderVendor::where(['vendor_id' => $request->vendor_id, 'order_id' => $request->order_id])->first();
            if ($currentOrderStatus->order_status_option_id == 2 && $request->status_option_id == 2) { //$request->status_option_id == 3){
                return response()->json(['status' => 'error', 'message' => __('Order has already been accepted!!!')]);
            }
            if ($currentOrderStatus->order_status_option_id == 3 && $request->status_option_id == 3) { //$request->status_option_id == 2){
                return response()->json(['status' => 'error', 'message' => __('Order has already been rejected!!!')]);
            }
            if (!$vendor_order_status_check) {
                DB::beginTransaction();
                $vendor_order_status                         = new VendorOrderStatus();
                $vendor_order_status->order_id               = $request->order_id;
                $vendor_order_status->vendor_id              = $request->vendor_id;
                $vendor_order_status->order_vendor_id        = $request->order_vendor_id;
                $vendor_order_status->order_status_option_id = $request->status_option_id;
                $vendor_order_status->save();
                if ($request->status_option_id == 2 || $request->status_option_id == 3) {
                    $clientDetail = CP::on('mysql')->where(['code' => $client_preferences->client_code])->first();
                    AutoRejectOrderCron::on('mysql')->where(['database_name' => $clientDetail->database_name, 'order_vendor_id' => $currentOrderStatus->id])->delete();
                }
                if ($request->status_option_id == 2) {
                    $order_dispatch = $this->checkIfanyProductLastMileon($request);
                    if ($order_dispatch && $order_dispatch == 1)
                        $stats = $this->insertInVendorOrderDispatchStatus($request);
                }
                OrderVendor::where('vendor_id', $request->vendor_id)->where('order_id', $request->order_id)->update(['order_status_option_id' => $request->status_option_id, 'reject_reason' => $request->reject_reason, 'cancelled_by' => 1]);
                $orderData = Order::find($request->order_id);

                $refundMsg = '';
                $dispatch_domain = $this->getDispatchDomain();
                if ($dispatch_domain && $dispatch_domain != false && ($request->status_option_id == 3)) {
                    $cancelled_by = 'Admin';
                    if (Auth::user()->is_superadmin == 0) {
                        $cancelled_by = 'Vendor :' . Auth::user()->name;
                    }
                    $order = Order::find($request->order_id);
                    $postdata =  [
                        'order_number'  => $order->order_number,
                        'cancel_reason' => OrderRejectingReason::where('id', $request->reject_reason)->value('name'),
                        'cancelled_by'  => $cancelled_by
                    ];
                    $client = new Client([
                        'headers' => [
                            'personaltoken' => $dispatch_domain->delivery_service_key,
                            'shortcode'     => $dispatch_domain->delivery_service_key_code,
                            'content-type'  => 'application/json'
                        ]
                    ]);
                    $url = $dispatch_domain->delivery_service_key_url;
                    $res = $client->post(
                        $url . '/api/reject-order',
                        ['form_params' => ($postdata)]
                    );
                    $response = json_decode($res->getBody(), true);

                    if ($order->payment_option_id == 4 && $order->paymentOption->code == 'stripe') {
                        $payment = Payment::where('order_id', $request->order_id)->first();
                        PaymentOptionController::StripeRefund($payment->transaction_id);
                        $refundMsg = 'Payment Refund initiated.';
                    }
                    $this->sendRejectOrderMail($request->order_id);
                }
                if ($request->status_option_id == 2) {
                    $this->ProductVariantStoke($request->order_id);
                }
                DB::commit();
                // $this->sendSuccessNotification(Auth::user()->id, $request->vendor_id);
                sendStatusChangePushNotificationCustomer([$currentOrderStatus->user_id], $orderData, $request->status_option_id, $request->vendor_id);

                return response()->json([
                    'status' => 'success',
                    'created_date' => convertDateTimeInTimeZone($vendor_order_status->created_at, $timezone, 'l, F d, Y, H:i A'),
                    'message' => 'Order Status Updated Successfully. ' . $refundMsg
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * 
     */
    public function sendRejectOrderMail($order_id,$type='rejected')
    {
        $cartDetails = [];
        $client = CP::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from', 'android_app_link', 'ios_link')->where('id', '>', 0)->first();
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $order = Order::with(['vendors.rejectingReason'])->where('id', $order_id)->first();
            $user = User::where('id', $order->user_id)->first();
            $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $currSymbol = 'AED';
            $client_name = $client->name;
            $mail_from = $data->mail_from;
            $android_app_link = $data->android_app_link;
            $ios_link = $data->ios_link;
            // Determine template ID based on type
            $templateId = ($type == 'rejected') ? 10 : 14;
            
            // Prepare product HTML
            $returnHTML = '';
            $rejecting_reason = '';
            
            foreach ($order->vendors as $key => $value) {
                if ($value->rejectingReason) {
                    $rejecting_reason = $value->rejectingReason->name;
                }
            }
            
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
                $this->sendEmail(
                    $user->email,
                    $templateId, // Template ID 10 for rejected, 14 for cancelled
                    [
                        'customer_name' => $user->name,
                        'order_id' => $order->order_number,
                        'rejecting_reason' => $rejecting_reason,
                        'products' => $returnHTML,
                        'order-cancelled-email-banner' => asset("images/email/order-$type-email-banner.jpg")
                    ],
                    [],
                    $user->language_id ?? null,
                    true,
                    'forgot_password_email'
                );
            } catch (Exception $th) {
                return response()->json(['success' => $th->getCode()], 200);
            }
        }
    }
    /// ******************   insert In Vendor Order Dispatch Status   ************************ ///////////////
    public function insertInVendorOrderDispatchStatus($request)
    {
        $update = VendorOrderDispatcherStatus::updateOrCreate([
            'dispatcher_id' => null,
            'order_id' =>  $request->order_id,
            'dispatcher_status_option_id' => 1,
            'vendor_id' =>  $request->vendor_id
        ]);
    }

    public function sendSuccessNotification($id, $vendorId)
    {
        $super_admin = User::where('is_superadmin', 1)->pluck('id');
        $user_vendors = UserVendor::where('vendor_id', $vendorId)->pluck('user_id');
        $devices = UserDevice::whereNotNull('device_token')->where('user_id', $id)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_vendors)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $super_admin)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $token[] = "d4SQZU1QTMyMaENeZXL3r6:APA91bHoHsQ-rnxsFaidTq5fPse0k78qOTo7ZiPTASiH69eodqxGoMnRu2x5xnX44WfRhrVJSQg2FIjdfhwCyfpnZKL2bHb5doCiIxxpaduAUp4MUVIj8Q43SB3dvvvBkM1Qc1ThGtEM";
        // dd($token);

        $from = env('FIREBASE_SERVER_KEY');

        $notification_content = NotificationTemplate::where('id', 2)->first();
        if ($notification_content) {
            $headers = [
                'Authorization: key=' . $from,
                'Content-Type: application/json',
            ];
            $data = [
                "registration_ids" => $token,
                "notification" => [
                    'title' => $notification_content->label,
                    'body'  => $notification_content->content,
                ]
            ];
            $dataString = $data;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
            $result = curl_exec($ch);
            // dd($result);
            curl_close($ch);
        }
    }
    /// ******************  check If any Product Last Mile on   ************************ ///////////////
    public function checkIfanyProductLastMileon($request)
    {
        $order_dispatchs = 2;
        $checkdeliveryFeeAdded = OrderVendor::where(['order_id' => $request->order_id, 'vendor_id' => $request->vendor_id])->first();
        $dispatch_domain = $this->getDispatchDomain();
        if ($dispatch_domain && $dispatch_domain != false) {
            //if ($checkdeliveryFeeAdded && $checkdeliveryFeeAdded->delivery_fee > 0.00)
            $order_dispatchs = $this->placeRequestToDispatch($request->order_id, $request->vendor_id, $dispatch_domain);


            if ($order_dispatchs && $order_dispatchs == 1)
                return 1;
        }


        $dispatch_domain_ondemand = $this->getDispatchOnDemandDomain();
        if ($dispatch_domain_ondemand && $dispatch_domain_ondemand != false) {
            $ondemand = 0;

            foreach ($checkdeliveryFeeAdded->products as $key => $prod) {
                if (isset($prod->product_dispatcher_tag) && !empty($prod->product_dispatcher_tag) && $prod->product->category->categoryDetail->type_id == 8) {
                    $dispatch_domain_ondemand = $this->getDispatchOnDemandDomain();
                    if ($dispatch_domain_ondemand && $dispatch_domain_ondemand != false && $ondemand == 0  && $checkdeliveryFeeAdded->delivery_fee <= 0.00) {
                        $order_dispatchs = $this->placeRequestToDispatchOnDemand($request->order_id, $request->vendor_id, $dispatch_domain_ondemand);
                        if ($order_dispatchs && $order_dispatchs == 1) {
                            $ondemand = 1;
                            return 1;
                        }
                    }
                }
            }
        }

        return 2;
    }
    // place Request To Dispatch
    public function placeRequestToDispatch($order, $vendor, $dispatch_domain)
    {
        try {

            $order       = Order::find($order);
            $customer    = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected       = 'Yes';
                $payable_amount             = $order->payable_amount;
                $sub_vendor_payment_type    = 'COD';
            } else {
                $cash_to_be_collected       = 'No';
                $payable_amount             = $order->payable_amount;
                $sub_vendor_payment_type    = 'PREPAID';
            }
            $dynamic        = uniqid($order->id . $vendor);
            $call_back_url  = route('dispatch-order-update', $dynamic);
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'latitude', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';
            // $team_tag = null;
            // if (!empty($dispatch_domain->last_mile_team))
            // {
            // $team_tag = $dispatch_domain->last_mile_team;
            $team_tag = $dispatch_domain->client_code . "_" . $vendor;
            // }

            $tasks[] = array(
                'task_type_id'  => 1,
                'latitude'      => $vendor_details->latitude ?? '',
                'longitude'     => $vendor_details->longitude ?? '',
                'short_name'    => '',
                'building_villa_flat_no' => '',
                'address'       => $vendor_details->address ?? '',
                'post_code'     => '',
                'barcode'       => '',
            );

            $deliveryAddress    = json_decode($order->delivery_address, true);
            $tasks[] = array(
                'task_type_id'  => 2,
                'latitude'      => $deliveryAddress['latitude'] ?? '',
                'longitude'     => $deliveryAddress['longitude'] ?? '',
                'short_name'    => $deliveryAddress['street'] . ',' . $deliveryAddress['city'],
                'building_villa_flat_no'       => $deliveryAddress['building_villa_flat_no'] ?? '',
                'address'       => $deliveryAddress['address'] ?? '',
                'post_code'     => $deliveryAddress['pincode'] ?? '',
                'barcode'       => '',
            );
            $postdata =  [
                'customer_name'             => $customer->name ?? 'Dummy Customer',
                'customer_phone_number'     => $customer->phone_number ?? rand(111111, 11111),
                'customer_email'            => $customer->email ?? null,
                'recipient_phone'           => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email'           => $customer->email ?? null,
                'task_description'          => "Order From :" . $vendor_details->name,
                'allocation_type'           => 'm',
                'task_type'                 => 'now',
                'cash_to_be_collected'      => $payable_amount ?? 0.00,
                'payment_method'            => $order->payment_method ?? 0,
                'barcode'                   => '',
                'order_team_tag'            => $team_tag,
                'call_back_url'             => $call_back_url ?? null,
                'task'                      => $tasks,
                'runrun_order_number'       => $order->order_number,
                'sub_vendor_payment_type'   => $sub_vendor_payment_type,
                'sub_vendor_payment_amount' => $payable_amount,
                'vehicle_number'            => $order->vehicle_number ?? null,
                'type'                      => $order->type,
                'special_instruction'       => $order->special_instruction
            ];

            $client = new Client([
                'headers' => [
                    'personaltoken' => $dispatch_domain->delivery_service_key,
                    'shortcode'     => $dispatch_domain->delivery_service_key_code,
                    'content-type'  => 'application/json'
                ]
            ]);
            Log::info("postdata" . json_encode($postdata));
            $url      = $dispatch_domain->delivery_service_key_url;
            $res      = $client->post($url . '/api/task/create', ['form_params' => ($postdata)]);
            $response = json_decode($res->getBody(), true);
            if ($response && $response['task_id'] > 0) {
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic]);
                return 1;
            }
            return 2;
        } catch (\Exception $e) {
            return 2;
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }



    // place Request To Dispatch for On Demand
    public function placeRequestToDispatchOnDemand($order, $vendor, $dispatch_domain)
    {
        try {

            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected = 'Yes';
                $payable_amount = $order->payable_amount;
            } else {
                $cash_to_be_collected = 'No';
                $payable_amount = 0.00;
            }
            $dynamic = uniqid($order->id . $vendor);
            $call_back_url = route('dispatch-order-update', $dynamic);
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'latitude', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';

            $unique = Auth::user()->code;
            $team_tag = $unique . "_" . $vendor;


            $tasks[] = array(
                'task_type_id' => 1,
                'latitude' => $vendor_details->latitude ?? '',
                'longitude' => $vendor_details->longitude ?? '',
                'short_name' => '',
                'address' => $vendor_details->address ?? '',
                'post_code' => '',
                'barcode' => '',
            );

            $tasks[] = array(
                'task_type_id' => 2,
                'latitude' => $cus_address->latitude ?? '',
                'longitude' => $cus_address->longitude ?? '',
                'short_name' => '',
                'address' => $cus_address->address ?? '',
                'post_code' => $cus_address->pincode ?? '',
                'barcode' => '',
            );

            $postdata =  [
                'customer_name' => $customer->name ?? 'Dummy Customer',
                'customer_phone_number' => $customer->phone_number ?? rand(111111, 11111),
                'customer_email' => $customer->email ?? null,
                'recipient_phone' => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email' => $customer->email ?? null,
                'task_description' => "Order From :" . $vendor_details->name,
                'allocation_type' => 'a',
                'task_type' => 'now',
                'cash_to_be_collected' => $payable_amount ?? 0.00,
                'barcode' => '',
                'order_team_tag' => $team_tag,
                'call_back_url' => $call_back_url ?? null,
                'task' => $tasks,
                'runrun_order_number' => $order->order_number
            ];


            $client = new Client([
                'headers' => [
                    'personaltoken' => $dispatch_domain->dispacher_home_other_service_key,
                    'shortcode' => $dispatch_domain->dispacher_home_other_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);

            $url = $dispatch_domain->dispacher_home_other_service_key_url;
            $res = $client->post(
                $url . '/api/task/create',
                ['form_params' => ($postdata
                )]
            );
            $response = json_decode($res->getBody(), true);
            if ($response && $response['task_id'] > 0) {
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic]);
                return 1;
            }
            return 2;
        } catch (\Exception $e) {
            return 2;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    # get prefereance if last mile on or off and all details updated in config
    public function getDispatchDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }


    # get prefereance if on demand on in config
    public function getDispatchOnDemandDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_dispacher_home_other_service == 1 && !empty($preference->dispacher_home_other_service_key) && !empty($preference->dispacher_home_other_service_key_code) && !empty($preference->dispacher_home_other_service_key_url))
            return $preference;
        else
            return false;
    }



    /**
     * Display a listing of the order return request.
     *
     * @return \Illuminate\Http\Response
     */
    public function returnOrders(Request $request, $domain = '', $status)
    {
        try {

            $orders_list = OrderReturnRequest::where('status', $status)->with('product')->orderBy('updated_at', 'DESC');
            if (Auth::user()->is_superadmin == 0) {
                $orders_list = $orders_list->whereHas('order.vendors.vendor.permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }
            $orders[$status] = $orders_list->paginate(20);
            return view(
                'backend.order.return',
                [
                    'orders' => $orders,
                    'status' => $status
                ]
            );
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }


    /**
     * return orders details
     */
    public function getReturnProductModal(Request $request, $domain = '')
    {
        try {
            $return_details = OrderReturnRequest::where('id', $request->id)->with('returnFiles')->first();
            if (isset($return_details)) {

                if ($request->ajax()) {
                    return response()->json(view('frontend.modals.update-return-product-client', array('return_details' => $return_details))->render());
                }
            }
            return $this->errorResponse('Invalid order', 404);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * return  order product
     */
    public function updateProductReturn(Request $request)
    {
        DB::beginTransaction();
        try {
            $return = OrderReturnRequest::find($request->id);
            $returns = OrderReturnRequest::where('id', $request->id)->update(['status' => $request->status ?? null, 'reason_by_vendor' => $request->reason_by_vendor ?? null]);
            if (isset($returns)) {
                if ($request->status == 'Accepted' && $return->status != 'Accepted') {
                    $user = User::find($return->return_by);
                    $wallet = $user->wallet;
                    $order_product = OrderProduct::find($return->order_vendor_product_id);
                    $credit_amount = $order_product->price + $order_product->taxable_amount;
                    $wallet->depositFloat($credit_amount, ['Wallet has been <b>Credited</b> for return ' . $order_product->product_name]);
                }
                DB::commit();
                return $this->successResponse($returns, 'Updated.');
            }
            return $this->errorResponse('Invalid order', 200);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function sendStatusChangePushNotificationCustomer1($user_ids, $orderData, $order_status_id)
    {
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_ids)->pluck('device_token')->toArray();
        // Log::info($devices);
        $client_preferences = ClientPreference::select('fcm_server_key', 'favicon')->first();
        if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
            $from = $client_preferences->fcm_server_key;
            if ($order_status_id == 2) {
                $notification_content = NotificationTemplate::where('id', 5)->first();
            } elseif ($order_status_id == 3) {
                $notification_content = NotificationTemplate::where('id', 6)->first();
            } elseif ($order_status_id == 4) {
                $notification_content = NotificationTemplate::where('id', 7)->first();
            } elseif ($order_status_id == 5) {
                $notification_content = NotificationTemplate::where('id', 8)->first();
            } elseif ($order_status_id == 6) {
                $notification_content = NotificationTemplate::where('id', 9)->first();
            }
            if ($notification_content) {
                $body_content = str_ireplace("{order_id}", "#" . $orderData->order_number, $notification_content->content);
                // $data = [
                //     "registration_ids" => $devices,
                //     "notification" => [
                //         'title' => $notification_content->subject,
                //         'body'  => $body_content,
                //         'sound' => "default",
                //         "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                //         'click_action' => route('user.orders'),
                //         "android_channel_id" => "default-channel-id"
                //     ],
                //     "data" => [
                //         'title' => $notification_content->subject,
                //         'body'  => $body_content,
                //         "type" => "order_status_change"
                //     ],
                //     "priority" => "high"
                // ];
                // $dataString = $data;
                $notification = Notification::fromArray([
                    'title' => $notification_content->subject,
                    'body'  => $body_content,
                ]);
                $data = [
                    'title' => $notification_content->subject,
                    'body'  => $body_content,
                    "type" => "order_status_change"
                ];
                try {
                    $fcm = FirebaseService::connect();
                    foreach ($devices as $device) {
                        $message = CloudMessage::withTarget('token', $device)
                            ->withNotification($notification)
                            ->withData($data)
                            ->withAndroidConfig([
                                'notification' => [
                                    'channel_id' => 'default-channel-id',
                                    // "priority" => "normal"
                                ]
                            ]);
                        $send = $fcm->send($message);
                    }
                } catch (\Exception $e) {
                    Log::info('Error in FCM: ' . $e->getMessage());
                }
            }
        }
    }

}