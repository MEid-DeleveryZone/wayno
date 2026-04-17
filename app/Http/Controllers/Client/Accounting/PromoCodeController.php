<?php

namespace App\Http\Controllers\Client\Accounting;
use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Vendor;
use App\Models\OrderVendor;
use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Models\OrderStatusOption;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderPromoCodeExport;
use App\Models\DispatcherStatusOption;

class PromoCodeController extends Controller{

    public function index(Request $request){
        $order_status_options = OrderStatusOption::where('type', 1)->get();

        //  promo_code_uses_count
        $promo_code_uses_count = OrderVendor::distinct('coupon_code');
        if (Auth::user()->is_superadmin == 0) {
            $promo_code_uses_count = $promo_code_uses_count->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $promo_code_uses_count = $promo_code_uses_count->count('coupon_code');

        /// unique_users_to_use_promo_code_count 
        $unique_users_to_use_promo_code_count = OrderVendor::whereNotNull('coupon_id')->distinct('user_id');
        if (Auth::user()->is_superadmin == 0) {
            $unique_users_to_use_promo_code_count = $unique_users_to_use_promo_code_count->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $unique_users_to_use_promo_code_count = $unique_users_to_use_promo_code_count->count('user_id');

        /// admin_paid_total_amt 
        $admin_paid_total_amt = OrderVendor::where('coupon_paid_by', 1);
        if (Auth::user()->is_superadmin == 0) {
            $admin_paid_total_amt = $admin_paid_total_amt->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $admin_paid_total_amt = $admin_paid_total_amt->sum('discount_amount');

    /// vendor_paid_total_amt 
        $vendor_paid_total_amt = OrderVendor::where('coupon_paid_by', 0);
        if (Auth::user()->is_superadmin == 0) {
            $vendor_paid_total_amt = $vendor_paid_total_amt->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendor_paid_total_amt = $vendor_paid_total_amt->sum('discount_amount');

        /// promo_code_options 
        $promo_code_options = OrderVendor::whereNotNull('coupon_id')->distinct('coupon_id');
        
        if (Auth::user()->is_superadmin == 0) {
            $promo_code_options = $promo_code_options->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $promo_code_options = $promo_code_options->get();

        

        return view('backend/accounting/promocode', compact('vendor_paid_total_amt','admin_paid_total_amt','promo_code_uses_count','unique_users_to_use_promo_code_count','order_status_options','promo_code_options'));
    }

    public function filter(Request $request){
        try {
            $user = Auth::user();
            $search_value = $request->get('search');
            $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
            $vendor_orders_query = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment','orderstatus']);
            if (Auth::user()->is_superadmin == 0) {
                $vendor_orders_query = $vendor_orders_query->whereHas('vendor.permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }

            if (!empty($request->get('date_filter'))) {
                $date_date_filter = explode(' to ', $request->get('date_filter'));
                $to_date = (!empty($date_date_filter[1]))?$date_date_filter[1]:$date_date_filter[0];
                $from_date = $date_date_filter[0];
                $vendor_orders_query->between($from_date." 00:00:00", $to_date." 23:59:59");
            }
            $vendor_orders = $vendor_orders_query->orderBy('id', 'desc')->get();
            foreach ($vendor_orders as $vendor_order) {
                $order_status = '';
                $vendor_order->created_date = convertDateTimeInTimeZone($vendor_order->created_at, $timezone, 'Y-m-d h:i:s A');
                $vendor_order->user_name = $vendor_order->user ? $vendor_order->user->name : '';
                $vendor_order->view_url = route('order.show.detail', [$vendor_order->order_id, $vendor_order->vendor_id]);
                if($vendor_order->coupon_paid_by == 0){
                    $vendor_order->vendor_paid_promo = $vendor_order->discount_amount ?  $vendor_order->discount_amount : '0.00';
                    $vendor_order->admin_paid_promo = '0.00';
                }else{
                    $vendor_order->admin_paid_promo = $vendor_order->discount_amount ?  $vendor_order->discount_amount : '0.00';
                    $vendor_order->vendor_paid_promo = '0.00';
                }
                if($vendor_order->orderstatus){
                    $order_status_detail = $vendor_order->orderstatus->where('order_id', $vendor_order->order_id)->orderBy('id', 'DESC')->first();
                    if($order_status_detail){
                        $order_status_option = OrderStatusOption::where('id', $order_status_detail->order_status_option_id)->first();
                        if($order_status_option){
                            $order_status = $order_status_option->title;
                        }
                    }
                }
                $vendor_order->order_status = $order_status;
            }
            return Datatables::of($vendor_orders)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('promo_code_filter'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['coupon_id'], $request->get('promo_code_filter')) ? true : false;
                        });
                    }
                    if (!empty($request->get('status_filter'))) {
                        $status_fillter = $request->get('status_filter');
                        $instance->collection = $instance->collection->filter(function ($row) use ($status_fillter) {
                            return Str::contains($row['order_status'], $status_fillter) ? true : false;
                        });
                    }
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request){
                            if (Str::contains(Str::lower($row['order_detail']['order_number']), Str::lower($request->get('search')))){
                                return true;
                            }else if (Str::contains(Str::lower($row['user_name']), Str::lower($request->get('search')))) {
                                return true;
                            }else if (Str::contains(Str::lower($row['vendor']['name']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                })->make(true);
        } catch (Exception $e) {
            
        }
    }
    public function export() {
        return Excel::download(new OrderPromoCodeExport, 'promocode.xlsx');
    }
}
