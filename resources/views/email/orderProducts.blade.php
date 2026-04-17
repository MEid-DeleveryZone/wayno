<p>Vendor&nbsp; :&nbsp; {{$DeliveryCart->vendor->name}}
<p>Service&nbsp; :&nbsp; {{$DeliveryCart->product->title}}
@if(!empty($pick_up))
<p>Pick up Location&nbsp; :&nbsp; {{$pick_up}}
@endif
@if(!empty($drop_off))
<p>Drop off Location :&nbsp; {{$drop_off}}
@endif
<p>
    <table class="order-detail" border="1" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px; margin-top: 10px">
       
        <tr class="pad-left-right-space ">
            <td class="m-t-5" colspan="2" align="center">
                <p style="font-size: 14px;"><b>Service Fee</b></p>
            </td>
            <td class="m-t-5" colspan="2"  align="center">
                
                <b style>{{$currencySymbol . ' ' . number_format($order->total_amount, 2, '.', '')}}</b>
            </td>
        </tr>
        @if($order->total_discount > 0)
        <tr class="pad-left-right-space">
            <td colspan="2" align="center">
                <p style="font-size: 14px;"><b>Total Discount</b></p>
            </td>
            <td colspan="2" align="center">
                <b>{{$currencySymbol . ' ' . number_format($order->total_discount, 2, '.', '')}}</b>
            </td>
        </tr>
        @endif
        <tr class="pad-left-right-space">
            <td colspan="2" align="center">
                <p style="font-size: 14px;"><b>VAT</b></p>
            </td>
            <td colspan="2" align="center">
                <b>{{$currencySymbol . ' ' . number_format($order->taxable_amount, 2, '.', '')}}</b>
            </td>
        </tr>
        @if($order->total_delivery_fee > 0)
            <tr class="pad-left-right-space">
                <td colspan="2" align="center">
                    <p style="font-size: 14px;"><b>Delivery Charge</b></p>
                </td>
                <td colspan="2" align="center">
                    <b>{{$currencySymbol . ' ' . number_format($order->total_delivery_fee, 2, '.', '')}}</b>
                </td>
            </tr>
        @endif
        @if($order->tip_amount > 0)
            <tr class="pad-left-right-space">
                <td colspan="2" align="center">
                    <p style="font-size: 14px;"><b>Tip</b></p>
                </td>
                <td colspan="2" align="center">
                    <b>{{$currencySymbol . ' ' . number_format($order->tip_amount, 2, '.', '')}}</b>
                </td>
            </tr>
        @endif
        @if($order->subscription_discount > 0)
            <tr class="pad-left-right-space">
                <td colspan="2" align="center">
                    <p style="font-size: 14px;"><b>Subscription Discount</b></p>
                </td>
                <td colspan="2" align="center">
                    <b>{{$currencySymbol . ' ' . number_format($order->subscription_discount, 2, '.', '')}}</b>
                </td>
            </tr>
        @endif
        @if($order->loyalty_amount_saved > 0)
        <tr class="pad-left-right-space">
            <td colspan="2" align="center">
                <p style="font-size: 14px;"><b>Loyalty Amount Used</b></p>
            </td>
            <td colspan="2" align="center">
                <b>{{$currencySymbol . ' ' . number_format($order->loyalty_amount_saved, 2, '.', '')}}</b>
            </td>
        </tr>
        @endif
        @if($order->wallet_amount_used > 0)
        <tr class="pad-left-right-space">
            <td colspan="2" align="center">
                <p style="font-size: 14px;"><b>Wallet Amount Used</b></p>
            </td>
            <td colspan="2" align="center">
                <b>{{$currencySymbol . ' ' . number_format($order->wallet_amount_used, 2, '.', '')}}</b>
            </td>
        </tr>
        @endif
        <tr class="pad-left-right-space main-bg-light">
            <td class="m-b-5" colspan="2" align="center">
                <p style="font-size: 14px;"><b>Total</b></p>
            </td>
            <td class="m-b-5" colspan="2" align="center">
                <b>{{$currencySymbol . ' ' . number_format($order->payable_amount, 2, '.', '')}}</b>
            </td>
        </tr>
    </table>
</p>
