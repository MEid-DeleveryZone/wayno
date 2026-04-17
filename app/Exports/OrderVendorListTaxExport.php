<?php

namespace App\Exports;
use App\Models\OrderVendor;
use App\Models\OrderStatusOption;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Str;


class OrderVendorListTaxExport implements FromCollection,WithHeadings,WithMapping{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $filters;

    // Constructor to accept filters
    public function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function collection(){
        $user = Auth::user();
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $vendor_orders =  OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment'])->orderBy('id', 'DESC');
        if (Auth::user()->is_superadmin == 0) {
            $vendor_orders = $vendor_orders->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        // Apply date filter
        if (!empty($this->filters['date_filter'])) {
            $dates = explode(' to ', $this->filters['date_filter']);
            if (count($dates) == 2) {
                $vendor_orders->whereBetween('created_at', [$dates[0]." 00:00:00", $dates[1]." 23:59:59"]);
            }else{
                $vendor_orders->whereBetween('created_at', [$dates[0]." 00:00:00", $dates[0]." 23:59:59"]);
            }
        }
        // Apply vendor filter
        if (!empty($this->filters['vendor_id'])) {
            $vendor_orders->where('vendor_id', $this->filters['vendor_id']);
        }
        
        // Fetch the filtered data
        $vendor_orders = $vendor_orders->get();

        foreach ($vendor_orders as $vendor_order) {
            $vendor_order->created_date = convertDateTimeInTimeZone($vendor_order->created_at, $timezone, 'Y-m-d h:i:s A');
            $vendor_order->user_name = $vendor_order->user ? $vendor_order->user->name : '';
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
        // Apply status filter
        if (!empty($this->filters['status_filter'])) {
            $statusFilter = $this->filters['status_filter'];
            $vendor_orders = $vendor_orders->filter(function ($item)  use ($statusFilter){
                return Str::contains(Str::lower($item['order_status']), Str::lower($statusFilter));
            });
        }
        // Apply search filter
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
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
        return $vendor_orders;
    }

    public function headings(): array{
        return [
            'Order Id',
            'Date & Time',
            'Customer Name',
            'Vendor Name',
            'Subtotal Amount',
            'Promo Code Discount',
            'Tax',
            'Admin Commission [Fixed]',
            'Admin Commission [%Age]',
            'Final Amount',
            'Payment Method',
            'Order Status'
        ];
    }

    public function map($order_vendors): array
    {
        return [
            $order_vendors->orderDetail ? $order_vendors->orderDetail->order_number : '',
            $order_vendors->created_date,
            $order_vendors->user_name,
            $order_vendors->vendor ? $order_vendors->vendor->name : '',
            number_format($order_vendors->subtotal_amount, 2),
            number_format($order_vendors->discount_amount, 2),
            number_format($order_vendors->taxable_amount, 2),
            number_format($order_vendors->admin_commission_fixed_amount, 2),
            number_format($order_vendors->admin_commission_percentage_amount, 2),
            number_format($order_vendors->payable_amount, 2),
            $order_vendors->orderDetail->paymentOption ? $order_vendors->orderDetail->paymentOption->title : '',
            $order_vendors->order_status,
        ];
    }
}
