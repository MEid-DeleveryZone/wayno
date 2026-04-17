<?php

namespace App\Http\Controllers\Client\Accounting;
use DataTables;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\LoyaltyCard;
use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use App\Exports\OrderLoyaltyExport;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LoyaltyController extends Controller{

    public function index(Request $request){
        $loyalty_card_details = LoyaltyCard::get();
        
        // total_loyalty_spent 
        $total_loyalty_spent = Order::orderBy('id','desc');
        if (Auth::user()->is_superadmin == 0) {
            $total_loyalty_spent = $total_loyalty_spent->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $total_loyalty_spent =$total_loyalty_spent->sum('loyalty_points_used');


        // total_loyalty_earned 
        $total_loyalty_earned = Order::orderBy('id','desc');
        if (Auth::user()->is_superadmin == 0) {
            $total_loyalty_earned = $total_loyalty_earned->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $total_loyalty_earned =$total_loyalty_earned->sum('loyalty_points_earned');

        

        $payment_options = PaymentOption::where('status', 1)->get();

         // type_of_loyality_applied_count 
         $type_of_loyality_applied_count = Order::orderBy('id','desc');
         if (Auth::user()->is_superadmin == 0) {
             $type_of_loyality_applied_count = $type_of_loyality_applied_count->whereHas('vendors.vendor.permissionToUser', function ($query) {
                 $query->where('user_id', Auth::user()->id);
             });
         }
         $type_of_loyality_applied_count =$type_of_loyality_applied_count->distinct('loyalty_membership_id')->count('loyalty_membership_id');
 
        

        return view('backend.accounting.loyality',compact('loyalty_card_details', 'total_loyalty_earned','total_loyalty_spent','type_of_loyality_applied_count', 'payment_options'));
    }

    public function filter(Request $request){
        $month_number = '';
        $user = Auth::user();
        $search_value = $request->get('search');
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $month_picker_filter = $request->month_picker_filter;
        if($month_picker_filter){
            $temp_arr = explode(' ', $month_picker_filter);
            $month_number =  getMonthNumber($temp_arr[0]);
        }
        $orders_query = Order::with('user','paymentOption','loyaltyCard');
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
        $orders = $orders_query->orderBy('id', 'desc')->get();
        foreach ($orders as $order) {
            $order->loyalty_membership = $order->loyaltyCard ? $order->loyaltyCard->name : '';
            $order->loyalty_points_used = $order->loyalty_points_used ? $order->loyalty_points_used : '0.00';
            $order->created_date = convertDateTimeInTimeZone($order->created_at, $timezone, 'Y-m-d h:i:s A');
            $order->loyalty_points_earned = $order->loyalty_points_earned ? $order->loyalty_points_earned : '0.00';
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
        return Excel::download(new OrderLoyaltyExport, 'loyality.xlsx');
    }
}
