<?php

namespace App\Exports;

use App\Models\OrderVendor;
use App\Models\OrderStatusOption;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Auth;
class OrderPromoCodeExport implements FromCollection, WithMapping, WithHeadings{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {   $vendor_orders = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment','orderstatus']);
        
        if (Auth::user()->is_superadmin == 0) {
            $vendor_orders = $vendor_orders->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendor_orders = $vendor_orders->get();
        foreach ($vendor_orders as $vendor_order) {
            if($vendor_order->coupon_paid_by == 0){
                $vendor_order->vendor_paid_promo = $vendor_order->discount_amount ?  $vendor_order->discount_amount : '0.00';
                $vendor_order->admin_paid_promo = '0.00';
            }else{
                $vendor_order->admin_paid_promo = $vendor_order->discount_amount ?  $vendor_order->discount_amount : '0.00';
                $vendor_order->vendor_paid_promo = '0.00';
            }
            $vendor_order->payment_method = $vendor_order->orderDetail->paymentOption->title;
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
        return $vendor_orders;
    }
    public function headings(): array{
        return [
            'Order Id',
            'Date & Time',
            'Customer Name',
            'Vendor Name',
            'Subtotal Amount',
            'Promo Code Discount [Vendor Paid Promos]',
            'Promo Code Discount [Admin Paid Promos]',
            'Final Amount',
            'Payment Method',
            'Order Status',
        ];
    }

    public function map($order): array
    {
        return [
            $order->orderDetail->order_number,
            $order->created_at,
            $order->user ? $order->user->name : '',
            $order->vendor->name,
            $order->subtotal_amount,
            $order->admin_paid_promo,
            $order->vendor_paid_promo,
            $order->payable_amount,
            $order->payment_method,
            $order->order_status,
        ];
    }
    
}
