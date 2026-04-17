<?php

namespace App\Http\Controllers\Client\Accounting;
use DataTables;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderTax;
use App\Models\LoyaltyCard;
use Illuminate\Support\Str; 
use App\Models\TaxCategory;
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderVendorTaxExport;

class TaxController extends Controller{

    public function index(Request $request){
        $tax_category_options = TaxCategory::get();

        // total_tax_collected 
        $total_tax_collected = Order::orderBy('id','desc');
        if (Auth::user()->is_superadmin == 0) {
            $total_tax_collected = $total_tax_collected->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $total_tax_collected =$total_tax_collected->sum('taxable_amount');


        // type_of_taxes_applied_count
        $type_of_taxes_applied_count = OrderTax::distinct('tax_category_id');
        if (Auth::user()->is_superadmin == 0) {
            $type_of_taxes_applied_count = $type_of_taxes_applied_count->whereHas('order.vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $type_of_taxes_applied_count =$type_of_taxes_applied_count->count('tax_category_id');

        $payment_options = PaymentOption::where('status', 1)->get();

        return view('backend.accounting.tax', compact('total_tax_collected','payment_options','tax_category_options', 'type_of_taxes_applied_count'));
    }

    public function filter(Request $request){
        $user = Auth::user();
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $orders_query = Order::with('user','paymentOption','taxes');
        if (Auth::user()->is_superadmin == 0) {
            $orders_query = $orders_query->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        if (!empty($request->get('date_filter'))) {
            $date_date_filter = explode(' to ', $request->get('date_filter'));
            $to_date = (!empty($date_date_filter[1]))?$date_date_filter[1]:$date_date_filter[0];
            $from_date = $date_date_filter[0];
            $orders_query->between($from_date." 00:00:00", $to_date." 23:59:59");
        }
        if (!empty($request->get('tax_type_filter'))) {
            $tax_type_filter = $request->get('tax_type_filter');
            $orders_query->whereHas('taxes', function($q) use($tax_type_filter){
                if($tax_type_filter){
                    $q->where('tax_category_id', $tax_type_filter);
                }
            });
        }
        $orders = $orders_query->orderBy('id', 'desc')->get();
        foreach ($orders as $order) {
            $order->payment_method = $order->paymentOption ? $order->paymentOption->title : '';
            $order->customer_name = $order->user ? $order->user->name : '-';
            $order->created_date = convertDateTimeInTimeZone($order->created_at, $timezone, 'Y-m-d h:i:s A');
            $tax_types = [];
            foreach ($order->taxes as $tax) {
                if($tax){
                    $tax_types[]= $tax->category->title;
                }
            }
            $order->tax_types = implode(', ',$tax_types);
        }
        return Datatables::of($orders)
        ->addIndexColumn()
        ->filter(function ($instance) use ($request) {
            if (!empty($request->get('search'))) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request){
                    if (Str::contains(Str::lower($row['order_number']), Str::lower($request->get('search')))){
                        return true;
                    }elseif(Str::contains(Str::lower($row['user']['name']), Str::lower($request->get('search')))){
                        return true;
                    }
                    return false;
                });
            }
            if (!empty($request->get('payment_option'))) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request){
                    if (Str::contains(Str::lower($row['payment_option_id']), Str::lower($request->get('payment_option')))){
                        return true;
                    }
                    return false;
                });
            }
        })->make(true);
    }

    public function export() {
        return Excel::download(new OrderVendorTaxExport, 'tax.xlsx');
    }
}
