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
use App\Http\Requests\Web\OrderProductRatingRequest;
use App\Http\Requests\Web\OrderProductReturnRequest;
use App\Models\{Client, ClientPreference, EmailTemplate, NotificationTemplate, Order, OrderProductRating, VendorOrderStatus, OrderProduct, OrderProductRatingFile, ReturnReason, OrderReturnRequest, OrderReturnRequestFile, OrderVendor, OrderVendorProduct, User, UserDevice, UserVendor};
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ReturnOrderController extends BaseController
{

    use ApiResponser;

    /**
     * order details in modal
     */
    public function getOrderDatainModel(Request $request)
    {
        try {
            $user = Auth::user();
            $lang_id = $user->language;
            $order_details = Order::with([
                'vendors.products.productReturn', 'products.productRating', 'user', 'address',
                'vendors' => function ($qw) use ($request) {
                    $qw->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
                }, 'vendors.products' => function ($qw) use ($request) {
                    $qw->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
                }, 'vendors.products.translation' => function ($qw) use ($lang_id) {
                    $qw->where('language_id', $lang_id);
                }, 'products' => function ($qw) use ($request) {
                    $qw->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
                }
            ])->whereHas('vendors', function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id)->where('order_id', $request->id);
            })
                ->where('orders.user_id', Auth::user()->id)->where('orders.id', $request->id)->orderBy('orders.id', 'DESC')->first();
            if (isset($order_details)) {
                $arrayVendor = $order_details->vendors;
                foreach ($arrayVendor as $key => $value) {
                    $vendorProducts = [];
                    $start = 0;
                    foreach ($value->products as $p) {
                        $quantity = $p->quantity;
                        $p->quantity = 1;
                        // $vendorProducts = array_merge($vendorProducts, array_fill($start, $quantity, $p));
                        $vendorProducts = array_merge($vendorProducts, $this->fillProductArrayForReturn($start, $quantity, $p));
                        $start += $quantity;
                    }
                    $arrayVendor[$key] = collect($arrayVendor[$key])->put('products', $vendorProducts);
                }

                $order_details = collect($order_details)->put('vendors', $arrayVendor);
                return $this->successResponse($order_details, 'Return Data.');
            }
            return $this->errorResponse('Invalid order', 404);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Generate duplicate entries for products with return_ref_id unique value
     */

    public function fillProductArrayForReturn($start, $quantity, $product)
    {
        $arr = array();
        $temp = $product->toArray();
        for ($i = 0; $i < $quantity; $i++) {
            $checkReturn = OrderReturnRequest::where('order_vendor_product_id', $temp['id'])
                ->where('order_id', $temp['order_id'])
                ->where('return_ref_id', $i + 1)
                ->count();
            if (!$checkReturn) {
                $product = collect($product)->put('product_return', null);
                $product = collect($product)->put('checked', false);
            } else {
                foreach ($temp['product_return'] as $pr) {
                    if ($pr['return_ref_id'] == $i + 1) {
                        $product = collect($product)->put('product_return', $pr);
                        break;
                    }
                }
                $product = collect($product)->put('checked', true);
            }
            $product = collect($product)->put('return_ref_id', $i + 1);
            $arr[$start++] = $product;
        }
        return $arr;
    }


    /**
     * order details in for return order
     */
    public function getReturnProducts(Request $request, $domain = '')
    {
        try {
            if ($request->has('return_ids')) {
                $totalAmount = 0;
                foreach ($request->return_ids as $pId) {
                    $orderPdt = OrderProduct::find($pId);
                    $totalAmount += $orderPdt->prorated_price;
                }
            }
            $reasons = ReturnReason::where('status', 'Active')->orderBy('order', 'asc')->get();
            $order_details = Order::with(['vendors.products' => function ($q1) use ($request) {
                $q1->whereIn('id', $request->return_ids);
            }, 'products' => function ($q1) use ($request) {
                $q1->whereIn('id', $request->return_ids);
            }, 'products.productRating', 'user', 'address'])
                ->whereHas('vendors.products', function ($q) use ($request) {
                    $q->whereIn('id', $request->return_ids);
                })->where('orders.user_id', Auth::user()->id)->where('id', $request->order_id)->orderBy('orders.id', 'DESC')->first();

            if (isset($order_details)) {
                $data = ['order' => $order_details, 'reasons' => $reasons, 'selected_refund_amount' => $totalAmount];
                return $this->successResponse($data, 'Return Product.');
            }
            return $this->errorResponse('Invalid order', 404);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    /**
     * return  order product 
     */
    public function updateProductReturn(OrderProductReturnRequest $request)
    {
        $maxReturnDays = ClientPreference::pluck('max_return_days')->pop();

        $order_return_products = json_decode(json_encode($request->order_vendor_product_id), false);
        $order_return_products =  json_decode($order_return_products);

        foreach ($order_return_products as $row) {
            if ($row->checked) {
                $returnProducts[] = $row;
            }
        }

        try {
            $user = Auth::user();
            $order_deliver = 0;

            if ($returnProducts) {
                $order_details = OrderProduct::where('id', $returnProducts[0]->id)->whereHas('order', function ($q) {
                    $q->where('user_id', Auth::id());
                })->first();
            }

            if ($order_details)
                $today = date('Y-m-d');
            $expiry_date = date('Y-m-d', strtotime($order_details->created_at . '+' . $maxReturnDays . ' days'));
            if ($today > $expiry_date) {
                throw new \Exception("Cannot return! Date expired!");
            }
            $order_deliver = VendorOrderStatus::where(['order_id' => $order_details->order_id, 'vendor_id' => $order_details->vendor_id, 'order_status_option_id' => 5])->count();

            if ($order_deliver > 0) {
                foreach ($returnProducts as $row) {
                    $returns = OrderReturnRequest::updateOrCreate([
                        'order_vendor_product_id' => $row->id,
                        'order_id' => $order_details->order_id,
                        'return_ref_id' => $row->return_ref_id,
                        'return_by' => Auth::id()
                    ], ['reason' => $request->reason ?? null, 'coments' => $request->coments ?? null]);
                }

                // $returns = OrderReturnRequest::updateOrCreate([
                //     'order_vendor_product_id' => $request->order_vendor_product_id,
                //     'order_id' => $order_details->order_id,
                //     'return_by' => Auth::id()
                // ], ['reason' => $request->reason ?? null, 'coments' => $request->coments ?? null]);

                if (isset($request->add_files) && is_array($request->add_files))    # send  array of insert images 
                {
                    foreach ($request->add_files as $storage) {
                        $img = new OrderReturnRequestFile();
                        $img->order_return_request_id = $returns->id;
                        $img->file = $storage;
                        $img->save();
                    }
                }

                if (isset($request->remove_files) && is_array($request->remove_files))    # send index array of deleted images 
                    $removefiles = OrderReturnRequestFile::where('order_return_request_id', $returns->id)->whereIn('id', $request->remove_files)->delete();
            }

            if (isset($returns)) {
                $this->sendSuccessNotification($user->id, $order_details->vendor_id);
                $this->sendSuccessEmail($request);
                return $this->successResponse($returns, 'Return Submitted.');
            }
            return $this->errorResponse('Invalid order', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
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

        $notification_content = NotificationTemplate::where('id', 3)->first();
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

    public function sendSuccessEmail($request)
    {
        if ((isset($request->auth_token)) && (!empty($request->auth_token))) {
            $user = User::where('auth_token', $request->auth_token)->first();
        } else {
            $user = Auth::user();
        }
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        $message = __('An OTP has been sent to your email. Please check.');
        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $sendto =  $user->email;
            $client_name = 'Sales';
            $mail_from = $data->mail_from;
            try {
                $order_vendor_product = OrderVendorProduct::where('id', $request->order_vendor_product_id)->first();
                
                if ($order_vendor_product) {
                    // Use new EmailService for multi-language and RTL support
                    $this->sendEmail(
                        $user->email,
                        4, // Return order email template ID
                        [
                            'product_image' => $order_vendor_product->image['image_fit'] . '200/200' . $order_vendor_product->image['image_path'],
                            'product_name' => $order_vendor_product->product->title,
                            'price' => $order_vendor_product->price
                        ],
                        ['link' => "link"],
                        $user->language_id ?? null,
                        true,
                        'verify_email'
                    );
                    $notified = 1;
                }
            } catch (\Exception $e) {
                Log::error('Return order email error: ' . $e->getMessage());
            }
        }
    }
}
