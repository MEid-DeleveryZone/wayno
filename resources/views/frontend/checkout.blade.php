@extends('layouts.store', ['title' => 'Checkout'])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
@endsection
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @if(isset($set_template)  && $set_template->template_id == 1)
        @include('layouts.store/left-sidebar-template-one')
        @elseif(isset($set_template)  && $set_template->template_id == 2)
        @include('layouts.store/left-sidebar')
        @else
        @include('layouts.store/left-sidebar-template-one')
        @endif
</header>
<style type="text/css">
    .productVariants .firstChild {
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }
    .product-right .color-variant li,
    .productVariants .otherChild {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }
    .productVariants .otherSize {
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }
    .product-right .size-box ul li.active {
        background-color: inherit;
    }
</style>
<section class="section-b-space">
    <div class="container">
        <div class="checkout-page">
            <div class="checkout-form">
                <form method="post" action="{{route('user.placeorder')}}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <div class="checkout-title">
                                <h3>Billing Details</h3>
                            </div>
                           <div class="typography-box">
                            <div class="headings">
                                <h3>Add New Address</h3>
                            </div>
                            <script type="text/template" id="address_template">
                                <div class="row">
                                    <div>
                                        <input type="radio" name="gender" id="one" value="<%= address.id %>">
                                        <label for="one"> <%= address.address %> <%= address.street %> <%= address.city %> <%= address.state %> <%= address.pincode %> </label>
                                    </div>
                                </div>
                            </script>
                            <div class="typo-content input_button" id="address_template_main_div">
                                @forelse($addresses as $address)
                                    <div class="row">
                                        <div>
                                            <input type="radio" name="address_id" id="address" value="{{$address->id}}" checked="checked" required="">
                                            <label for="address">{{$address->address.' '.$address->street.' '.$address->city.' '.$address->state.' '.$address->pincode}}</label>
                                        </div>
                                    </div>
                                @empty
                                <h5>You haven't added any address yet.</h5>
                                @endforelse
                            </div>
                            <a class="btn btn-outline mr-3" id="add_new_address">+ Add New Address</a>
                            <div class="theme-card" id="add_new_address_form" style="display:none;">
                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" id="address" placeholder="Address" value="">
                                        <div class="input-group-append">
                                            <button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="edit"> <i class="mdi mdi-map-marker-radius"></i></button>
                                        </div>
                                        <span class="text-danger" id="address_error"></span>
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="col-md-6">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" placeholder="City" value="">
                                        <span class="text-danger" id="city_error"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" id="state" placeholder="State" value="">
                                        <span class="text-danger" id="state_error"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="country">Country</label>
                                        <select name="country" id="country" class="form-control" required="">
                                            @foreach($countries as $co)
                                                <option value="{{$co->id}}" selected>{{$co->name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger" id="country_error"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pincode">Pincode</label>
                                        <input type="text" class="form-control" id="pincode" placeholder="Pincode" value="">
                                        <span class="text-danger" id="pincode_error"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="type">Address Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="1" selected>Home</option>
                                            <option value="2">Office</option>
                                        </select>
                                        <span class="text-danger" id="type_error"></span>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-12 mt-3">
                                        <button type="button" class="btn btn-solid" id="save_address">Save Address</button>
                                        <button type="button" class="btn btn-solid black-btn" id="cancel_save_address_btn">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <div class="checkout-details">
                                <div class="order-box">
                                    <div class="title-box">
                                        <div>Product <span>Total</span></div>
                                    </div>
                                    <script type="text/template" id="checkout_products_template">
                                        <% _.each(products, function(product, key){%>
                                        <% _.each(product.vendor_products, function(vendor_product, key){%>
                                            <li><%= vendor_product.product.sku %> × <%= vendor_product.quantity %> <span>$<%= product.product_total_amount %></span></li>
                                        <% }); %>
                                        <% }); %>
                                    </script>
                                    <ul class="qty checkout-products" id="checkout_products_main_div">
                                        
                                    </ul>
                                    <ul class="sub-total">
                                        <li>Subtotal <span class="count checkout-total"></span></li>
                                        <li>Shipping
                                            <div class="shipping">
                                                <div class="shopping-option">
                                                    <input type="checkbox" name="free-shipping" id="free-shipping">
                                                    <label for="free-shipping">Free Shipping</label>
                                                </div>
                                                <div class="shopping-option">
                                                    <input type="checkbox" name="local-pickup" id="local-pickup">
                                                    <label for="local-pickup">Local Pickup</label>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <ul class="total">
                                        <li>Total <span class="count" id="total_payable_amount"></span></li>
                                    </ul>
                                </div>
                                <div class="payment-box">
                                    <div class="upper-box">
                                        <div class="payment-options">
                                            <ul>
                                                <!-- <li>
                                                    <div class="radio-option">
                                                        <input type="radio" name="payment-group" value="1" id="payment-1" checked="checked">
                                                        <label for="payment-1">Check Payments
                                                            <span class="small-text">Please send a check to Store Name, Store Street, Store Town, Store State /County, Store Postcode.</span>
                                                        </label>
                                                    </div>
                                                </li> -->
                                                <li>
                                                    <div class="radio-option">
                                                        <input type="radio" name="payment-group" value="2" id="payment-2" checked="">
                                                        <label for="payment-2">Cash On Delivery
                                                            <span class="small-text">Please send a check to Store
                                                                Name, Store Street, Store Town, Store State / County, Store Postcode.</span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <!-- <li>
                                                    <div class="radio-option paypal">
                                                        <input type="radio" name="payment-group" value="3" id="payment-3">
                                                        <label for="payment-3">PayPal<span class="image"><img src="{{asset('front-assets/images/paypal.png')}}" alt=""></span></label>
                                                    </div>
                                                </li> -->
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn-solid btn">Place Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script>
    var total1 = 0;
    var user_store_address_url = "{{url('user/store')}}";
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "get",
            url: "{{ route('getCartProducts') }}",
            data: '',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.res == "null") {
                    $(".checkout-products").html(data.html);
                } else {
                    var products = data.products;
                    let checkout_products_template = _.template($('#checkout_products_template').html());
                    if(products.length > 0){
                        $("#checkout_products_main_div").html(checkout_products_template({products:products}));
                    }
                    $('#total_payable_amount').html('$'+data.total_payable_amount)
                    $('#total_payable_amount_input').html('$'+data.total_payable_amount)
                }
            },
            error: function(data) {
                console.log('Error Found : ' + data);
            }
        });
    });
</script>
@endsection