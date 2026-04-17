<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderLoyaltyExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $user = Auth::user();
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $orders = Order::with('user','paymentOption','loyaltyCard')->orderBy('id', 'desc');
        if (Auth::user()->is_superadmin == 0) {
            $orders = $orders->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $orders = $orders->get();

        foreach ($orders as $order) {
            $order->loyalty_membership = $order->loyaltyCard ? $order->loyaltyCard->name : '';
            $order->loyalty_points_used = $order->loyalty_points_used ? $order->loyalty_points_used : '0.00';
            $order->created_date = convertDateTimeInTimeZone($order->created_at, $timezone, 'Y-m-d h:i:s A');
            $order->loyalty_points_earned = $order->loyalty_points_earned ? $order->loyalty_points_earned : '0.00';
        }
        return $orders;
    }
    public function headings(): array{
        return [
            'Order Id',
            'Date & Time',
            'Customer Name',
            'Loyalty Used',
            'Loyalty Membership',
            'Total Billing Amount',//Loyalty Earned
            'Final Amount',
            'Payment Method',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->created_date,
            $order->user ? $order->user->name : '',
            $order->loyalty_points_used,
            $order->loyalty_membership,
            $order->loyalty_points_earned,
            $order->payable_amount,
            $order->paymentOption ? $order->paymentOption->title: '',
        ];
    }
}
