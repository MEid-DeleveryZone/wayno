@extends('layouts.vertical', ['title' => 'Orders'])
@section('content') 
@php
$timezone = Auth::user()->timezone;
@endphp   
<style type="text/css">
    .ellipsis{
        white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
</style>
    <div class="container-fluid">
       
      <!-- Return Page Tabbar start from here -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title">{{__('Return Requests')}}</h4>                    
                </div>
            </div>
        </div>   
        <div class="row mb-lg-5">
            <div class="col-sm-12 col-lg-12 tab-product pt-0">
                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link @if($status == 'Pending') active @endif" id="awaiting_review" 
                            href="{{route('backend.order.returns',['Pending'])}}" role="tab" aria-selected="true"><i
                                class="icofont icofont-ui-home"></i>{{__('Pending Requests')}}</a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item"><a class="nav-link @if($status == 'Accepted') active @endif" id="processed-tab" 
                            href="{{route('backend.order.returns',['Accepted'])}}" role="tab" aria-selected="false"><i
                                class="icofont icofont-man-in-glasses"></i>{{__('Active Requests')}}</a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item"><a class="nav-link @if($status == 'Rejected') active @endif" id="rejected-tab" 
                            href="{{route('backend.order.returns',['Rejected'])}}" role="tab" aria-selected="false"><i
                                class="icofont icofont-man-in-glasses"></i>{{__('Rejected Requests')}}</a>
                        <div class="material-border"></div>
                    </li>
                </ul>
                <div class="tab-content nav-material" id="top-tabContent">
                    <div class="tab-pane fade @if($status == 'Pending') show active @endif" id="awaiting-review" role="tabpanel"
                        aria-labelledby="awaiting_review">
                        <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="w-100 common-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{__('Order id')}}</th>
                                            <th>{{__('Vendor')}}</th>
                                            <th>{{__('Customer Name')}}</th>
                                            <th>{{__('Product')}}</th>
                                            <th>{{__('Product Price')}}</th>
                                            <th>{{__('Date & Time')}}</th>
                                            <th>{{__('Request Date & Time')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($orders['Pending']) && count($orders['Pending']))    
                                        @foreach ($orders['Pending'] as $order)
                                        <tr data-id="{{ $order->id }}" class="show-return-product-modal"  data-status="Pending">
                                            <td>
                                                #{{$order->order->order_number??''}}
                                            </td>
                                            <td class="vendor-name">
                                                <a class="round_img_box mb-1" href="{{ route('vendor.show', $order->product->vendor_id) }}"><img class="rounded-circle" src="{{$order->product->vendor->logo['proxy_url'].'90/90'.$order->product->vendor->logo['image_path']}}" alt="{{$order->product->vendor->id}}"></a>
                                                <a href="{{ route('vendor.show', $order->product->vendor_id) }}">{{$order->product->vendor->name??''}}</a>
                                            </td>
                                           
                                            <td>
                                                <a href="#">{{$order->returnBy->name??''}}</a>
                                            </td>
                                           <td class="product-name">
                                                <img class="d-block mb-1" src="{{ $order->product->image['proxy_url'].'50/50'.$order->product->image['image_path']}}">
                                                <b class="text-capitalize">
                                                    {{$order->product->product_name??''}}
                                                </b>
                                            </td>
                                            <td class="">
                                                <b class="text-black">${{$order->product->price??''}}</b>
                                            </td>
                                            <td>{{ convertDateTimeInTimeZone($order->order->created_at, $timezone, 'd-m-Y, H:i A')}}</td>
                                            <td>{{ convertDateTimeInTimeZone($order->created_at, $timezone, 'd-m-Y, H:i A')}}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr><td colspan="7">{{__('No Records Found')}}</td></tr>
                                        @endif     
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="tab-pane fade @if($status == 'Accepted') show active @endif" id="processed" role="tabpanel"
                        aria-labelledby="processed-tab">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="w-100 common-table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{__('Order id')}}</th>
                                                <th>{{__('Vendor')}}</th>
                                                <th>{{__('Customer Name')}}</th>
                                                <th>{{__('Product')}}</th>
                                                <th>{{__('Product Price')}}</th>
                                                <th>{{__('Date & Time')}}</th>
                                                <th>{{__('Request Date & Time')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($orders['Accepted']) && count($orders['Accepted']))    
                                            @foreach ($orders['Accepted'] as $order)
                                            <tr data-id="{{ $order->id }}" class="show-return-product-modal"  data-status="Accepted">
                                                <td>
                                                    #{{$order->order->order_number??''}}
                                                </td>
                                                <td class="vendor-name">
                                                    <a class="round_img_box mb-1" href="{{ route('vendor.show', $order->product->vendor_id) }}"><img class="rounded-circle" src="{{$order->product->vendor->logo['proxy_url'].'90/90'.$order->product->vendor->logo['image_path']}}" alt="{{$order->product->vendor->id}}"></a>
                                                    <a href="{{ route('vendor.show', $order->product->vendor_id) }}">{{$order->product->vendor->name??''}}</a>
                                                </td>
                                               
                                                <td>
                                                    <a href="#">{{$order->returnBy->name??''}}</a>
                                                </td>
                                               <td class="product-name">
                                                    <img class="d-block mb-1" src="{{ $order->product->image['proxy_url'].'50/50'.$order->product->image['image_path']}}">
                                                    <b class="text-capitalize">
                                                        {{$order->product->product_name??''}}
                                                    </b>
                                                </td>
                                                <td class="">
                                                    <b class="text-black">${{$order->product->price??''}}</b>
                                                </td>
                                                <td>{{ convertDateTimeInTimeZone($order->order->created_at, $timezone, 'd-m-Y, H:i A')}}</td>
                                                <td>{{ convertDateTimeInTimeZone($order->created_at, $timezone, 'd-m-Y, H:i A')}}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                        <tr><td colspan="7">{{__('No Records Found')}}</td></tr>
                                        @endif    
                                       
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                    </div>
                    <div class="tab-pane fade @if($status == 'Rejected') show active @endif" id="rejected" role="tabpanel"
                        aria-labelledby="rejected-tab">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="w-100 common-table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{__('Order id')}}</th>
                                                <th>{{__('Vendor')}}</th>
                                               <th>{{__('Customer Name')}}</th>
                                                <th>{{__('Product')}}</th>
                                                <th>{{__('Product Price')}}</th>
                                                <th>{{__('Date & Time')}}</th>
                                                <th>{{__('Request Date & Time')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($orders['Rejected']) && count($orders['Rejected']))    
                                            @foreach ($orders['Rejected'] as $order)
                                            <tr data-id="{{ $order->id }}" class="show-return-product-modal"  data-status="Rejected">
                                                <td>
                                                    #{{$order->order->order_number??''}}
                                                </td>
                                                <td class="vendor-name">
                                                    <a class="round_img_box mb-1" href="{{ route('vendor.show', $order->product->vendor_id) }}"><img class="rounded-circle" src="{{$order->product->vendor->logo['proxy_url'].'90/90'.$order->product->vendor->logo['image_path']}}" alt="{{$order->product->vendor->id}}"></a>
                                                    <a href="{{ route('vendor.show', $order->product->vendor_id) }}">{{$order->product->vendor->name??''}}</a>
                                                </td>
                                               
                                                <td>
                                                    <a href="#">{{$order->returnBy->name??''}}</a>
                                                </td>
                                               <td class="product-name">
                                                    <img class="d-block mb-1" src="{{ $order->product->image['proxy_url'].'50/50'.$order->product->image['image_path']}}">
                                                    <b class="text-capitalize">
                                                        {{$order->product->product_name??''}}
                                                    </b>
                                                </td>
                                                <td class="">
                                                    <b class="text-black">${{$order->product->price??''}}</b>
                                                </td>
                                                <td>{{ convertDateTimeInTimeZone($order->order->created_at, $timezone, 'd-m-Y, H:i A')}}</td>
                                                <td>{{ convertDateTimeInTimeZone($order->created_at, $timezone, 'd-m-Y, H:i A')}}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                        <tr><td colspan="7">{{__('No Records Found')}}</td></tr>
                                        @endif   
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                    </div>
                </div>
                <div class="pagination pagination-rounded justify-content-end mb-0">
                    {{ $orders[$status]->links() }}
                </div>
            </div>
        </div>   
       
    </div> 
<!-- product return modal -->
<div class="modal fade return-order" id="return_order" tabindex="-1" aria-labelledby="return_orderLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                <div id="return-order-form-modal">

                </div>    
               

            </div>
        </div>
    </div>
</div>
<!-- end product return modal -->
    @endsection

    @section('script')
    
    <script type="text/javascript">

    //// ************  return product details   *****************  //
        $('body').on('click', '.show-return-product-modal', function (event) {
            $(".vendor-name").click(function(e) { e.stopPropagation(); });
                event.preventDefault();
                var id = $(this).data('id');
                var status = $(this).data('status');
                var returnurl = "{{route('get-return-product-modal')}}";
                $.get(returnurl+'?id=' + id +'&status=' + status, function(markup)
                        {   
                            $('#return_order').modal('show'); 
                            $('#return-order-form-modal').html(markup);
                        });
            });    
</script>
@endsection
