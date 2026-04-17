<div class="card-box text-center p-0 overflow-hidden" style="">
    <div class="background pt-3 pb-2 px-2" style="background:url({{$vendor->banner['proxy_url'] . '200/100' . $vendor->banner['image_path']}}) no-repeat center center;background-size:cover;">
        <div class="vendor_text">
            <img src="{{$vendor->logo['proxy_url'] . '90/90' . $vendor->logo['image_path']}}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
            <h4 class="mb-0 text-white">{{ucfirst($vendor->name)}}</h4>
            <p class="text-white">{{$vendor->address}}</p>
            @if($vendor->status == 0 && Auth::user()->is_superadmin == 1)
            <button type="button" class="btn btn-success btn-sm waves-effect mb-2 waves-light" id="approve_btn" data-vendor_id="{{$vendor->id}}" data-status="1">{{ __("Accept") }}</button>
            <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light" id="reject_btn" data-vendor_id="{{$vendor->id}}" data-status="2">{{ __("Reject") }}</button>
            @else
            @if(Auth::user()->is_superadmin == 1)
            <button type="button" class="btn btn-success btn-sm waves-effect mb-2 waves-light openEditModal" data-toggle="modal" data-target="#exampleModal"> {{ __("Edit") }} </button>
            <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light" id="block_btn" data-vendor_id="{{$vendor->id}}" data-status="{{$vendor->status == 2  ? '1' : '2'}}">{{$vendor->status == 2 ? 'Unblock' : 'Block'}}</button>
            @endif
            @if($client_preferences->need_dispacher_ride == 1)
            <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light openConfirmDispatcher" data-id="{{ $vendor->id }}"> {{ __("Login Into Dispatcher (Pickup & Delivery)") }} </button>
            @endif
            @if($client_preferences->need_dispacher_home_other_service == 1)
            <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light openConfirmDispatcherOnDemand" data-id="{{ $vendor->id }}"> {{ __("Login Into Dispatcher (On Demand Services)") }} </button>
            @endif
            @endif
        </div>
    </div>
    <div class="text-left mt-0 p-3">
        <p class="text-muted font-13 mb-0">
            {{$vendor->desc}}
        </p>
    </div>
</div>
<!-- <div class="card-box">
    <div class="row text-left">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="mb-2">Public URL</h4>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="form-group mb-0">
                        <div class="site_link position-relative">
                            <a href="{{route('vendorDetail',$vendor->slug)}}" target="_blank"><span id="pwd_spn" class="password-span">{{route('vendorDetail',$vendor->slug)}}</span></a>
                            <label class="copy_link float-right" id="cp_btn" title="copy">
                                <img src="{{ asset('assets/icons/domain_copy_icon.svg')}}" alt="">
                                <span class="copied_txt" id="show_copy_msg_on_click_copy" style="display:none;">Copied</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="card-box">
    <div class="row text-left">
        <div class="col-md-12">
            <form name="config-form" action="{{route('vendor.config.update', $vendor->id)}}" class="needs-validation" id="slot-configs" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-2 "> <span class="">{{ __("Configuration") }}</span></h4>
                    </div>
                </div>
                <div class="row mb-2">
                    @if($client_preference_detail->business_type != 'taxi')
                    <div class="col-md-12">
                        <div class="form-group" id="order_pre_timeInput">
                            {!! Form::label('title', __('Order Prepare Time(In minutes)'),['class' => 'control-label']) !!}
                            <input class="form-control" onkeypress="return isNumberKey(event)" name="order_pre_time" type="text" value="{{ ($vendor->order_pre_time > 0) ? $vendor->order_pre_time : 0 }}" {{$vendor->status == 1 ? '' : 'disabled'}}>
                        </div>
                    </div>
                    @endif
                      <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
                        {!! Form::label('title', __('Cancel Order'),['class' => 'control-label']) !!}
                        <input type="checkbox" data-plugin="switchery" name="is_cancelOrder" class="form-control" data-color="#43bee1" @if($vendor->is_cancel == 1) checked @endif {{$vendor->status == 1 ? '' : 'disabled'}}>
                    </div>
                    @if($client_preference_detail->business_type != 'taxi')
                    <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
                        {!! Form::label('title', __('24*7 Availability'),['class' => 'control-label']) !!}
                        <input type="checkbox" data-plugin="switchery" name="show_slot" class="form-control" data-color="#43bee1" @if($vendor->show_slot == 1) checked @endif {{$vendor->status == 1 ? '' : 'disabled'}}>
                    </div>
                    @endif
                    @if($client_preference_detail->business_type != 'taxi')
                    <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
                        {!! Form::label('title', __('Auto Accept Order'),['class' => 'control-label']) !!}
                        <input type="checkbox" data-plugin="switchery" name="auto_accept_order" class="form-control" data-color="#43bee1" @if($vendor->auto_accept_order == 1) checked @endif {{$vendor->status == 1 ? '' : 'disabled'}}>
                    </div>
                    <div class="col-md-12" id="auto_reject_timeInput" style="display:{{$vendor->auto_accept_order == 1 ? 'none' : 'block'}}">
                        <div class="form-group">
                            {!! Form::label('title', __('Auto Reject Time(In minutes, 0 for no rejection)'),['class' => 'control-label']) !!}
                            <input class="form-control" name="auto_reject_time" type="number" value="{{$vendor->auto_reject_time}}" min="0" {{$vendor->status == 1 ? '' : 'disabled'}} >
                        </div>
                    </div>
                    @endif
                    <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
                        {!! Form::label('title', __('Show Profile Details'),['class' => 'control-label']) !!}
                        <input type="checkbox" data-plugin="switchery" name="is_show_vendor_details" class="form-control" data-color="#43bee1" @if($vendor->is_show_vendor_details == 1) checked @endif {{$vendor->status == 1 ? '' : 'disabled'}}>
                    </div>
                    <!-- <div class="col-md-12">
                        <div class="form-group" id="order_min_amountInput">
                            {!! Form::label('title', 'Order Min Amount',['class' => 'control-label']) !!}
                            <input class="form-control" onkeypress="return isNumberKey(event)" name="order_min_amount" type="text" value="{{$vendor->order_min_amount}}" {{$vendor->status == 1 ? '' : 'disabled'}}>
                        </div>
                    </div> -->
                </div>
                @if(Auth::user()->is_superadmin == 1)
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-2"> <span class="">{{ __("Payment Option") }}</span> ({{ __("Visible For Admin") }})</h4>
                        <div class="form-group"  id="payment_idsInput">
                            {!! Form::label('title', __('Select Payment methods'),['class' => 'control-label']) !!}
                            <select class="form-control select2-multiple" data-toggle="select2" id="paySelectBox" name="payment_ids[]" multiple="multiple" data-placeholder="Choose ..." required>
                                
                                @if(isset($paymentOptions) && !empty($paymentOptions))
                                    @foreach($paymentOptions as $option)
                                    <option value="{{$option->id}}" @if(!empty(json_decode($vendor->payment_options)) && in_array($option->id, json_decode($vendor->payment_options))) selected @endif>{{ ucfirst($option->code)}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="invalid-feedback" role="alert"><strong></strong></span>
                        </div>
            
                    </div>
                    <div class="col-md-12">
                        <h4 class="mb-2"> <span class="">{{ __("Commission") }}</span> ({{ __("Visible For Admin") }})</h4>
                    </div>
                </div>
                @endif
                <div class="row mb-2">
                    @if(Auth::user()->is_superadmin == 1)
                    <div class="col-md-12">
                        <!-- <div class="col-md-12">
                            <div class="form-group" id="commission_monthlyInput">
                                {!! Form::label('title', 'Commission Monthly',['class' => 'control-label']) !!}
                                <input class="form-control" onkeypress="return isNumberKey(event)" name="commission_monthly" type="text" value="{{$vendor->commission_monthly}}">
                            </div>
                        </div> -->
                        <div class="form-group" id="commission_percentInput">
                            {!! Form::label('title', __('Commission Percent'),['class' => 'control-label']) !!}
                            <input class="form-control" name="commission_percent" type="text" value="{{$vendor->commission_percent}}" onkeypress="return isNumberKey(event)">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="commission_fixed_per_orderInput">
                            {!! Form::label('title', __('Commission Fixed Per Order'),['class' => 'control-label']) !!}
                            <input class="form-control" name="commission_fixed_per_order" type="text" value="{{$vendor->commission_fixed_per_order}}" onkeypress="return isNumberKey(event)">
                        </div>
                    </div>
                    @endif
                    <div class="col-12">
                        <button class="btn btn-info waves-effect waves-light w-100" {{$vendor->status == 1 ? '' : 'disabled'}}>{{ __("Save") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@if(Auth::user()->is_superadmin == 1)

<style type="text/css">
    #nestable_list_1 ol,
    #nestable_list_1 ul {
        list-style-type: none;
    }
</style>
<div class="card-box">
    <div class="row text-left">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="mb-2"> <span class="">{{ __("Category Setup") }}</span> ({{ __("Visible For Admin") }})</h4>
                </div>
            </div> 
        </div>
    </div>
    <div class="row">
        @if($client_preference_detail->business_type != 'taxi')
        <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
            {!! Form::label('title', __('Can Add Category'),['class' => 'control-label']) !!}
            <input type="checkbox" data-plugin="switchery" name="can_add_category" class="form-control can_add_category1" data-color="#43bee1" @if($vendor->add_category == 1) checked @endif {{$vendor->status == 1 ? '' : 'disabled'}}>
        </div>
        <div class="col-md-6 mb-3">
            {!! Form::label('title', __('Vendor Detail To Show'),['class' => 'control-label ']) !!}
        </div>
        
        <div class="col-md-6 mb-3">
            <select class="selectize-select form-control assignToSelect" id="assignTo" {{$vendor->status == 1 ? '' : 'disabled'}}>
                @foreach($templetes as $templete)
                    <option value="{{$templete->id}}" {{$vendor->vendor_templete_id == $templete->id ? 'selected="selected"' : ''}}>{{$templete->title}}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-md-12">
            {!! Form::label('title', __('Vendor Category'),['class' => 'control-label']) !!}
            <div class="custom-dd dd nestable_list_1" id="nestable_list_1">
                <ol class="dd-list">
                    @forelse($builds as $build)
                    @if($build['translation_one'])
                    <li class="dd-item dd3-item" data-category_id="{{$build['id']}}">
                        <div class="dd3-content"> 
                            <img class="rounded-circle mr-1" src="{{$build['icon']['proxy_url']}}30/30{{$build['icon']['image_path']}}"> {{$build['translation_one']['name']}}
                            <span class="inner-div text-right">
                                <a class="action-icon" data-id="3" href="javascript:void(0)">
                                    @if(in_array($build['id'], $VendorCategory))
                                        <input type="checkbox" data-category_id="{{ $build['id'] }}" data-color="#43bee1" class="form-control activeCategory" data-plugin="switchery" checked {{$vendor->status == 1 ? '' : 'disabled'}}>
                                    @else
                                        <input type="checkbox" data-category_id="{{ $build['id'] }}" data-color="#43bee1" class="form-control activeCategory" data-plugin="switchery" {{$vendor->status == 1 ? '' : 'disabled'}}>
                                    @endif
                                    <input type="hidden" value="{{ $build['id'] }}">
                                </a>
                            </span> 
                        </div>
                        @if(isset($build['children']))
                            <x-category :categories="$build['children']" :vendorcategory="$VendorCategory" :vendor="$vendor"/>
                        @endif
                        </li>
                    </li>
                    @endif
                    @empty
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
</div>
@endif
<style type="text/css">
    #nestable_list_1 ol, #nestable_list_1 ul{
        list-style-type: none;
    }
</style>


 <div class="card-box">
    <h4 class="header-title mb-0 mt-2 d-inline-block align-middle">{{ __('Users') }}</h4>
    <h4 class="header-title mb-0 float-right"><a class="btn addUsersBtn" dataid="0" href="javascript:void(0);"><i class="mdi mdi-plus-circle mr-1" ></i> {{ __("Add Users") }}
    </a></h4>
    
    <div class="inbox-widget mt-3" data-simplebar style="max-height: 350px;">
        @foreach($vendor->permissionToUser as $users)
        <div class="inbox-item pb-0">
            <div class="inbox-item-img">
                <img src="{{$users->user ? $users->user->image['proxy_url'].'40/40'.$users->user->image['image_path'] : asset('assets/images/users/user-2.jpg')}}" class="rounded-circle" alt="">

                {{-- <img src="{{asset('assets/images/users/user-2.jpg')}}" class="rounded-circle" alt=""> --}}
            </div>
            <p class="inbox-item-author">{{ $users->user->name??'' }}  </p>
            <p class="inbox-item-text"><label class="d-block"><i class="fa fa-envelope mr-1" aria-hidden="true"></i> {{ $users->user->email??'' }}  @if($users->user->phone_number)</label><label class="d-block">  <i class="fa fa-phone mr-1" aria-hidden="true"></i> {{ $users->user->phone_number??'' }}</label> </p> @endif</p>
            @if($users->user->id != Auth::id())
            <form class="delete-user position-absolute" method="POST" action="{{route('user.vendor.permission.destroy', $users->id)}}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary-outline" onclick="return confirm('Are you sure ?');"> <i class="mdi mdi-delete"></i></button>
        
                    </form>
            @endif
        </div>
       
        @endforeach
    </div>
</div>

<div id="edit-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">

                @php
                $vendors = getNomenclatureName('vendors', false);
                $newvendors = ($vendors === "vendors") ? __('vendors') : $vendors ; 
                @endphp

                <h4 class="modal-title">{{ __("Edit") }} {{ $newvendors }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>


            <form id="save_edit_banner_form" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editCardBox">
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitEditForm">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="add-user-permission" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __("Add User") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="add_user_permission_vendor" method="post" enctype="multipart/form-data" action="{{route('permissionsForUserViaVendor')}}" autocomplete="off">
                @csrf
                <input type="hidden" name="vendor_id" value="{{$vendor->id}}" id="set-vendor_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-group" id="skuInput">
                                        {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => __('Search User'), 'id' => 'id_search_user_for_permission', 'required' => 'required']) !!}
                                        <input type="hidden" id='cusid' name="ids" readonly>
                                        <div id="userList">
                                        </div>
                                    </div>
                                </div>

                              
                            </div>
                        </div>
                    </div>
                </div>
                <span class="text-danger" id="error-msg"></span>
                <span class="text-success" id="success-msg"></span>
                <div class="modal-footer">
                    <button  class="btn btn-info waves-effect waves-light" id="user_permission_form_button">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    
$('.addUsersBtn').click(function() {
        $('#add-user-permission').modal({
            keyboard: false
        });
    });
    

    $( document ).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });
// search users for set permission 
$('#id_search_user_for_permission').keyup(function(){ 
    var query = $(this).val();
    var vendor_id = $('#set-vendor_id').val();
    if(query != '')
    {
     var _token = $('input[name="_token"]').val();
     $.ajax({
      url:"{{ route('searchUserForPermission') }}",
      method:"POST",
      data:{query:query, _token:_token, vendor_id:vendor_id},
      success:function(data){
       $('#userList').fadeIn();  
       $('#userList').html(data);
      }
     });
    }
});

$(document).on('click', 'li', function(){  
    $('#id_search_user_for_permission').val($(this).text());  
    $('#cusid').val($(this).attr('data-id'));
    $('#userList').fadeOut();  
});  


// submit permission for user 
$('#add_user_permission_vendor').submit(function(e) {
            
            e.preventDefault();

            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('permissionsForUserViaVendor') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                   $("#user_permission_form_button").html(
                            '<i class="fa fa-spinner fa-spin fa-custom"></i> Loading').prop(
                            'disabled', true);
                },
                success: (data) => {
                    if (data.status == 'Success') {
                       $("#user_permission_form_button").html('Submitted');
                       location.reload();
                    } else {
                        $('#error-msg').text(data.message);
                        $("#user_permission_form_button").html('Submit').prop('disabled',
                            false);
                    }
                },
                error: function(data) {
                    $('#error-msg').text(data.message);
                    $("#user_permission_form_button").html('Submit').prop('disabled',
                        false);
                }
            });
        });



    $(document).on('click', '#approve_btn, #reject_btn, #block_btn', function(){
        var that  = $(this);
        var status = that.data('status');
        var vendor_id = that.data('vendor_id');
        var text = that.text().toLowerCase();
        var message = "Are you sure want to "+text+" this vendor?";
        if(confirm(message)){
            $.ajax({
                type: "POST",
                url: "{{route('vendor.status')}}",
                data: { vendor_id: vendor_id , status:status},
                success: function(data) {
                    if(data.status == 'success'){
                       $.NotificationApp.send("Success", data.message, "top-right", "#5ba035", "success");
                       window.location.href = "{{ route('vendor.index') }}";
                    }
                }
            });
        }       
    });
    $(document).on('change', '.can_add_category1', function(){
        var vendor_id = "{{$vendor->id}}";
        var can_add_category = $(this).is(":checked");
        var url = "{{ url('client/vendor/activeCategory').'/'.$vendor->id}}"
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {vendor_id:vendor_id, can_add_category:can_add_category},
            success: function(response) {
                if (response.status == 'Success') {

                }
            }
        });
    });
    $(document).on('change', '#assignTo', function(){
        var assignTo = $(this).val();
        var vendor_id = "{{$vendor->id}}";
        var url = "{{ url('client/vendor/activeCategory').'/'.$vendor->id}}"
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {vendor_id:vendor_id, assignTo:assignTo},
            success: function(response) {
                if (response.status == 'Success') {
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    });
    $(document).on('change', '.activeCategory', function(){
        var vendor_id = "{{$vendor->id}}";
        var status = $(this).is(":checked");
        var category_id = $(this).data('category_id');
        var url = "{{ url('client/vendor/activeCategory').'/'.$vendor->id}}"
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {category_id: category_id, status:status, vendor_id:vendor_id},
            success: function(response) {
                if (response.status == 'Success') {
                    $('#category_list').html('');
                   $('#category_list').html('<option value="">Select Category...</option>');
                   $('#category_list').selectize()[0].selectize.destroy();
                   $.each(response.data, function (key, value) {
                        if(value.category.type_id == 1){
                           $('#category_list').append('<option value='+value.category_id+'>'+value.category.title+'</option>');
                        }
                   });
                }
            }
        });
    });
});

$("input[name='auto_accept_order']").change(function() {
    if($(this).prop('checked')){
        $("#auto_reject_timeInput").css("display", "none");
    } else {
        $("#auto_reject_timeInput").css("display", "block"); 
    }
})
</script>