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
use App\Models\DispatcherStatusOption;
use App\Models\ClientCurrency;
use App\Exports\OrderVendorListTaxExport;
use DB;

class OrderController extends Controller{
    use ApiResponser;
    public function index(Request $request){
        $total_order_count = 0;
        $total_delivery_fees = 0;
        $total_cash_to_collected = 0;
        $total_earnings_by_vendors = 0;
        $dispatcher_status_options = DispatcherStatusOption::get();
        $order_status_options = OrderStatusOption::where('type', 1)->get();
        // all vendors
        $vendors = Vendor::where('status', '!=', '2')->orderBy('id', 'desc');
        if (Auth::user()->is_superadmin == 0) {
            $vendors = $vendors->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendors = $vendors->get();

        // vendor orders
        $vendor_orders = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment']);
        if (Auth::user()->is_superadmin == 0) {
            $vendor_orders = $vendor_orders->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendor_orders =$vendor_orders->get();

        foreach ($vendor_orders as $vendor_order) {
            $total_delivery_fees+= $vendor_order->delivery_fee;
            $total_earnings_by_vendors+= $vendor_order->payable_amount;
            if($vendor_order->orderDetail->paymentOption){
                if($vendor_order->orderDetail->paymentOption->id == 1){
                    $total_cash_to_collected += $vendor_order->payable_amount;
                }
            }
        }
        $total_order_count = $vendor_orders->count();
        $clientCurrency = ClientCurrency::where('is_primary', 1)->with('currency')->first();
        if(isset($clientCurrency->currency) && $clientCurrency->currency->iso_code == "AED"){
            $CurremcySymbel = $clientCurrency->currency->iso_code;
        }else{
            $CurremcySymbel = $clientCurrency->currency->symbol;
        }
        return view('backend.accounting.order', compact('vendors','order_status_options', 'dispatcher_status_options'))
                    ->with(
                            ['total_earnings_by_vendors' => number_format($total_earnings_by_vendors, 2),
                             'total_delivery_fees' => number_format($total_delivery_fees, 2),
                             'total_cash_to_collected' => number_format($total_cash_to_collected, 2),
                             'CurremcySymbel'=>$CurremcySymbel,
                             'total_order_count' => $total_order_count, 2]);
    }
    public function filter(Request $request){
        $user = Auth::user();
        $search_value = $request->get('search');
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $vendor_orders_query = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment','orderstatus']);
        if (!empty($request->get('date_filter'))) {
            $date_date_filter = explode(' to ', $request->get('date_filter'));
            $to_date = (!empty($date_date_filter[1]))?$date_date_filter[1]:$date_date_filter[0];
            $from_date = $date_date_filter[0];
            $vendor_orders_query->between($from_date." 00:00:00", $to_date." 23:59:59");
        }
        if (!empty($request->get('vendor_id'))) {
            $vendor_orders_query->where('vendor_id',$request->get('vendor_id'));
        }
        if (Auth::user()->is_superadmin == 0) {
            $vendor_orders_query = $vendor_orders_query->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendor_orders = $vendor_orders_query->orderBy('id', 'DESC')->get();
        

        foreach ($vendor_orders as $vendor_order) {
            $vendor_order->created_date = convertDateTimeInTimeZone($vendor_order->created_at, $timezone, 'Y-m-d h:i:s A');
            $vendor_order->user_name = $vendor_order->user ? $vendor_order->user->name : '';
            $vendor_order->view_url = route('order.show.detail', [$vendor_order->order_id, $vendor_order->vendor_id]);
            $order_status = '';
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
        // Filter the orders based on the `order_status`
        if (!empty($request->get('status_filter'))) {
            $status_filter = $request->get('status_filter');
            $vendor_orders = $vendor_orders->filter(function ($order) use ($status_filter) {
                return Str::contains(Str::lower($order->order_status), Str::lower($status_filter));
            });
        }
        // Apply search filter
        if (!empty($request->get('search'))) {
            $search = $request->get('search');
            $vendor_orders = $vendor_orders->filter(function ($item)  use ($search){
                if(Str::contains(Str::lower($item->orderDetail['order_number']), Str::lower($search))){
                    return true;
                }elseif(Str::contains(Str::lower($item['user_name']), Str::lower($search))){
                    return true;
                }elseif(Str::contains(Str::lower($item->vendor['name']), Str::lower($search))){
                    return true;
                }
                return false;
            });
        }
        $total_order_count = $vendor_orders->count();
        $total_delivery_fees = $vendor_orders->sum('delivery_fee');
        $total_cash_to_collected = $vendor_orders->filter(function ($order) {
            return $order->orderDetail->paymentOption->id == 1; // Cash payment
        })->sum('payable_amount');
        $total_earnings_by_vendors = $vendor_orders->sum('payable_amount');
        return Datatables::of($vendor_orders)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                // if (!empty($request->get('vendor_id'))) {
                //     $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                //         return Str::contains($row['vendor_id'], $request->get('vendor_id')) ? true : false;
                //     });
                // }
                // if (!empty($request->get('status_filter'))) {
                //     $status_fillter = $request->get('status_filter');
                //     $instance->collection = $instance->collection->filter(function ($row) use ($status_fillter) {
                //         return Str::contains($row['order_status'], $status_fillter) ? true : false;
                //     });
                // }
                // if (!empty($request->get('search'))) {
                //     $instance->collection = $instance->collection->filter(function ($row) use ($request){
                //         if (Str::contains(Str::lower($row['order_detail']['order_number']), Str::lower($request->get('search')))){
                //             return true;
                //         }else if (Str::contains(Str::lower($row['user_name']), Str::lower($request->get('search')))) {
                //             return true;
                //         }else if (Str::contains(Str::lower($row['vendor']['name']), Str::lower($request->get('search')))) {
                //             return true;
                //         }
                //         return false;
                //     });
                // }
            })
            ->with([
                'total_earnings_by_vendors' => number_format($total_earnings_by_vendors,2),
                'total_order_count' => $total_order_count,
                'total_cash_to_collected' => number_format($total_cash_to_collected,2),
                'total_delivery_fees' => number_format($total_delivery_fees,2),
            ])
            ->make(true);
    }

    public function export(Request $request) {
        $filters = [
            'date_filter' => $request->get('date_filter'),
            'vendor_id' => $request->get('vendor_id'),
            'status_filter' => $request->get('status_filter'),
            'search' => $request->get('search'),
        ];
        return Excel::download(new OrderVendorListTaxExport($filters), 'order_list.xlsx');
    }
    
}
