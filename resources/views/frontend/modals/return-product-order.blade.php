<h5 class="modal-title">
    Do you want to return your order.
</h5>
<form class="return-order-form" action="{{route('get-return-products')}}" method="get">
    <div class="table-responsive">
        <table class="w-100">
            <thead>
                <tr>
                    <th>
                        Select item(s) for return
                    </th>
                    <th>order Information</th>
                    
                </tr>
            </thead>
            <tbody>
                @php $continue = 0; @endphp
            @foreach($order->vendors as $key => $vendor)    
                @foreach($vendor->products as  $key => $product)
               
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            @if(isset($product->productReturn->status))
                             {{ __($product->productReturn->status) }}
                            @else
                            @php $continue = 1; @endphp
                            <input id="item_one{{$key}}" type="radio" name="return_ids" value="{{ $product->id }}" required>
                            <input id="item_{{$key}}" type="hidden" name="order_id" value="{{ $order->id }}">
                            @endif
                            <label class="order-items d-flex" for="item_one{{$key}}">  
                                <div class="item-img mx-1">
                                    @if($product->pvariant->media->isNotEmpty())
                                        <img src="{{ $product->pvariant->media->first()->pimage->image->path['image_fit'].'74/100'.$product->pvariant->media->first()->pimage->image->path['image_path'] }}" alt="">
                                    @else
                                        <img src="{{ $product->image['image_fit'].'74/100'.$product->image['image_path'] }}" alt="">
                                    @endif
                                </div>    
                                <div class="items-name ml-2">
                                    <h4 class="mt-0 mb-1"><b>{{ $product->product_name }}</b></h4>
                                    <label><b>Quantity</b>: {{ $product->quantity }}</label>
                                </div>
                            </label>
                        </div>
                    </td>
                    <td class="order_address">
                        @if($order->address)
                            {{$order->address->address}}, {{$order->address->street}}, {{$order->address->city}}, {{$order->address->state}}, {{$order->address->country}} {{$order->address->pincode}}
                        @else
                        {{__('NA')}}
                        @endif
                    </td>
                    
                </tr>
                @endforeach  
            @endforeach  
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="3">
                        @if($continue == 1)
                        <button class="btn btn-solid" type="submit">Continue</button>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>