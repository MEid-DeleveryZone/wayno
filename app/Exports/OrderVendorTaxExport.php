<?php

namespace App\Exports;

use App\Models\OrderVendor;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Auth;
class OrderVendorTaxExport implements FromCollection,WithHeadings,WithMapping{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $vendor_orders = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment']);
        if (Auth::user()->is_superadmin == 0) {
            $vendor_orders = $vendor_orders->whereHas('vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendor_orders = $vendor_orders->get();
        return $vendor_orders;
    }

    public function headings(): array{
        return [
            'Order Id',
            'Date & Time',
            'Customer Name',
            'Final Amount',
            'Tax Amount',
            'Tax Types',
            'Payment Method'
        ];
    }
    public function map($order_vendors): array
    {
        return [
            $order_vendors->orderDetail ? $order_vendors->orderDetail->order_number : '',
            $order_vendors->orderDetail ? $order_vendors->orderDetail->created_at : '',
            $order_vendors->user ? $order_vendors->user->name : '',
            $order_vendors->payable_amount,
            $order_vendors->discount_amount,
            '',
            $order_vendors->orderDetail ? $order_vendors->orderDetail->paymentOption->title : '',
        ];
    }
}
