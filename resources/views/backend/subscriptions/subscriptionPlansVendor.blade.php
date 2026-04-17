@extends('layouts.vertical', ['title' => 'Subscriptions'])

@section('css')
<!-- Plugins css -->
<link href="{{asset('assets/libs/admin-resources/admin-resources.min.css')}}" rel="stylesheet" type="text/css" />

<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <div class="content dashboard-boxes">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ __("Vendor Subscription Plans") }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 text-sm-right">
                    <button class="btn btn-info waves-effect waves-light text-sm-right" data-toggle="modal" data-target="#add-subscription-plan">
                        <i class="mdi mdi-plus-circle mr-1"></i> {{ __("Add Plan") }}
                    </button>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="text-sm-left">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <span>{!! \Session::get('success') !!}</span>
                            </div>
                        @endif
                        @if (\Session::has('error_delete') || \Session::has('error'))
                            <div class="alert alert-danger">
                                <span>{!! \Session::get('error') !!}</span>
                            </div>
                        @endif
                        @if ( ($errors) && (count($errors) > 0) )
                            <div class="alert alert-danger">
                                <ul class="m-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card widget-inline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 mb-3 mb-md-0">
                                    <div class="text-center">
                                        <h3>
                                            <i class="mdi mdi-account-multiple-plus text-primary mdi-24px"></i>
                                            <span data-plugin="counterup" id="total_subscribed_vendors_count">{{ $subscribed_vendors_count }}</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">{{ __("Total Subscribed Vendors") }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 mb-3 mb-md-0">
                                    <div class="text-center">
                                        <h3>
                                            <i class="mdi mdi-account-multiple-plus text-primary mdi-24px"></i>
                                            <span data-plugin="counterup" id="total_subscribed_vendors_percentage">{{ $subscribed_vendors_percentage }}</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">{{ __("Total Subscribed Vendors") }} (%)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-lg-12 tab-product pt-0">
                    <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-plans" data-toggle="tab" href="#all_plans" role="tab" aria-selected="false" data-rel="sub-plans-datatable" data-status="">
                                <i class="icofont icofont-man-in-glasses"></i>{{ __("Plans") }}
                            </a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="awaiting-approval-subscriptions" data-toggle="tab" href="#awaiting_approval_subscriptions" role="tab" aria-selected="true" data-rel="awaiting_approval_subscriptions_datatable" data-status="1">
                                <i class="icofont icofont-ui-home"></i>{{ __("Awaiting Approval") }}<sup class="total-items">({{ $awaiting_approval_subscriptions_count }})</sup>
                            </a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="approved-subscriptions" data-toggle="tab" href="#approved_subscriptions" role="tab" aria-selected="false" data-rel="approved_subscriptions_datatable" data-status="2">
                                <i class="icofont icofont-man-in-glasses"></i>{{ __("Approved") }}<sup class="total-items">({{ $approved_subscriptions_count }})</sup>
                            </a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="rejected-subscriptions" data-toggle="tab" href="#rejected_subscriptions" role="tab" aria-selected="false" data-rel="rejected_subscriptions_datatable" data-status="4">
                                <i class="icofont icofont-man-in-glasses"></i>{{ __("Rejected") }}<sup class="total-items">({{ $rejected_subscriptions_count }})</sup>
                            </a>
                            <div class="material-border"></div>
                        </li>
                    </ul>
                    <div class="tab-content nav-material pt-0" id="top-tabContent">
                        <div class="tab-pane fade show active" id="all_plans" role="tabpanel" aria-labelledby="all-plans">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-centered table-nowrap table-striped" id="sub-plans-datatable" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __("Image") }}</th>
                                                            <th>{{ __("Title") }}</th>
                                                            <th>{{ __("Description") }}</th>
                                                            <th>{{ __("Price") }}</th>
                                                            <th>{{ __("Features") }}</th>
                                                            <th>{{ __("Frequency") }}</th>
                                                            <th>{{ __("Status") }}</th>
                                                            <th>{{ __("On Request") }}</th>
                                                            <th>{{ __("Action") }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($subscription_plans as $plan)
                                                        <tr data-row-id="{{$plan->slug}}">
                                                            <td> 
                                                                <img src="{{$plan->image['proxy_url'].'40/40'.$plan->image['image_path']}}" class="rounded-circle" alt="{{$plan->slug}}" >
                                                            </td>
                                                            <td><a href="javascript:void(0)" class="editSubscriptionPlanBtn" data-id="{{$plan->slug}}">{{$plan->title}}</a></td>
                                                            <td>{{$plan->Description}}</td>
                                                            <td>${{$plan->price}}</td>
                                                            <td>{{$plan->features}}</td>
                                                            <td>{{ucfirst($plan->frequency)}}</td>
                                                            <td>
                                                                <input type="checkbox" data-id="{{$plan->slug}}" data-plugin="switchery" name="vendorSubscriptionStatus" class="chk_box status_check" data-color="#43bee1" {{($plan->status == 1) ? 'checked' : ''}} >
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" data-id="{{$plan->slug}}" data-plugin="switchery" name="vendorSubscriptionOnRequest" class="chk_box on_request_check" data-color="#43bee1" {{($plan->on_request == 1) ? 'checked' : ''}} >
                                                            </td>
                                                            <td> 
                                                                <div class="form-ul" style="width: 60px;">
                                                                    <div class="inner-div" >
                                                                        @if(Auth::user()->is_superadmin == 1)
                                                                            <a href="javascript:void(0)" class="action-icon editSubscriptionPlanBtn" data-id="{{$plan->slug}}"><i class="mdi mdi-square-edit-outline"></i></a>
                                                                            <a href="{{route('subscription.plan.delete.vendor', $plan->slug)}}" onclick="return confirm('Are you sure? You want to delete the subscription plan.')" class="action-icon"> <i class="mdi mdi-delete" title="Delete subscription plan"></i></a>
                                                                        @endif    
                                                                    </div>
                                                                </div>
                                                            </td> 
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="awaiting_approval_subscriptions" role="tabpanel" aria-labelledby="awaiting-approval-subscriptions">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-centered table-nowrap table-striped" id="awaiting_approval_subscriptions_datatable" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Vendor Name') }}</th>
                                                            <th>{{ __('Plan') }}</th>
                                                            <th>{{ __('Price') }}</th>
                                                            <th>{{ __('Features') }}</th>
                                                            <th>{{ __('Frequency') }}</th>
                                                            <th>{{ __('Status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="approved_subscriptions" role="tabpanel" aria-labelledby="approved-subscriptions">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-centered table-nowrap table-striped" id="approved_subscriptions_datatable" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Vendor Name') }}</th>
                                                            <th>{{ __('Plan') }}</th>
                                                            <th>{{ __('Price') }}</th>
                                                            <th>{{ __('Features') }}</th>
                                                            <th>{{ __('Frequency') }}</th>
                                                            <th>{{ __('Status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="rejected_subscriptions" role="tabpanel" aria-labelledby="rejected-subscriptions">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-centered table-nowrap table-striped" id="rejected_subscriptions_datatable" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Vendor Name') }}</th>
                                                            <th>{{ __("Plan") }}</th>
                                                            <th>{{ __("Price") }}</th>
                                                            <th>{{ __("Features") }}</th>
                                                            <th>{{ __("Frequency") }}</th>
                                                            <th>{{ __("Status") }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container -->

    </div>

</div> <!-- container -->

<div id="add-subscription-plan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addVendorSubscription_Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Add Plan") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="vendor_subscription_form" method="post" enctype="multipart/form-data" action="{{ route('subscription.plan.save.vendor') }}">
                @csrf
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>{{ __("Upload Image") }}</label>
                                    <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                                    <label class="logo-size text-right w-100">{{ __("Image Size") }} 120x120</label>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', __('Enable'),['class' => 'control-label']) !!} 
                                        <div class="mt-md-1">
                                            <input type="checkbox" data-plugin="switchery" name="status" class="form-control status" data-color="#43bee1" checked='checked'>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', __('On Request'),['class' => 'control-label']) !!} 
                                        <div class="mt-md-1">
                                            <input type="checkbox" data-plugin="switchery" name="on_request" class="form-control on_request" data-color="#43bee1" checked='checked'>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="nameInput">
                                        {!! Form::label('title', __('Title'),['class' => 'control-label']) !!} 
                                        {!! Form::text('title', null, ['class'=>'form-control', 'required'=>'required']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">{{ __('Features') }}</label>
                                        <select class="form-control select2-multiple" name="features[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ..." required="required">
                                            @foreach($features as $feature)
                                                <option value="{{$feature->id}}"> {{$feature->title}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">{{ __("Price") }}</label>
                                        <input class="form-control" type="number" name="price" min="0" required="required">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">{{ __("Frequency") }}</label>
                                        <select class="form-control" name="frequency" required="required">
                                            <option value="weekly">{{ __("Weekly") }}</option>
                                            <option value="monthly">{{ __("Monthly") }}</option>
                                            <option value="yearly">{{ __("Yearly") }}</option>
                                        </select>
                                    </div>
                                </div>
                                <?php /* ?><div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Sort Order</label>
                                        <input class="form-control" type="number" name="sort_order" min="1" value="" required="required">
                                    </div>
                                </div><?php */ ?>
                                <div class="col-md-12">
                                    <div class="form-group" id="descInput">
                                        {!! Form::label('title', 'Description',['class' => 'control-label']) !!} 
                                        {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light submitAddSubscriptionForm">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-subscription-plan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editVendorSubscription_Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="modal fade" id="subscription-update" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="subscription_updateLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="subscription_updateLabel">{{ __("Action") }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form id="subscription-update-form" method="POST" action="">
        @csrf
        <div><input type="hidden" name="subscription_slug" id="subscription_slug" value=""></div>
        <div class="modal-body">
            <h6 class="m-0">{{ __("Choose an option for this subscription") }} : </h6>
            <div class="radio pl-1 mt-2 radio-blue form-check-inline">
                <input type="radio" name="subscription_status" id="radio-approve" value="approve" required>
                <label for="radio-approve"> {{ __("Approve") }} </label>
            </div>
            <div class="radio pl-1 mt-2 radio-blue form-check-inline">
                <input type="radio" name="subscription_status" id="radio-reject" value="reject" required>
                <label for="radio-reject"> {{ __("Reject & Refund") }} </label>
            </div>
        </div>
        <div class="modal-footer flex-nowrap justify-content-center align-items-center">
            <button type="submit" class="btn btn-success">{{ __("Continue") }}</a>
            <button type="button" class="btn btn-info" data-dismiss="modal">{{ __("Cancel") }}</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('script')

<script>
    var edit_subscription_url = "{{ route('subscription.plan.edit.vendor', ':id') }}";
    var update_subscription_status_url = "{{route('subscription.plan.updateStatus.vendor', ':id')}}";
    var update_subscription_onrequest_url = "{{route('subscription.plan.updateOnRequest.vendor', ':id')}}";
    var vendor_subscription_update_url = "{{ route('vendor.subscription.status.update', ':id') }}";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });
    $(document).delegate(".editSubscriptionPlanBtn", "click", function(){
        let slug = $(this).attr("data-id");
        $.ajax({
            type: "get",
            dataType: "json",
            url: edit_subscription_url.replace(":id", slug),
            success: function(res) {
                $("#edit-subscription-plan .modal-content").html(res.html);
                $("#edit-subscription-plan").modal("show");
                $('#edit-subscription-plan .select2-multiple').select2();
                $('#edit-subscription-plan .dropify').dropify();
                var switchery1 = new Switchery($("#edit-subscription-plan .status")[0]);
                var switchery2 = new Switchery($("#edit-subscription-plan .on_request")[0]);
            }
        });
    });

    $("#sub-plans-datatable .status_check").on("change", function() {
        var slug = $(this).attr('data-id');
        var status = 0;
        if($(this).is(":checked")){
            status = 1;
        }
        $.ajax({
            type: "post",
            dataType: "json",
            url: update_subscription_status_url.replace(":id", slug),
            data: {status: status},
            success: function(response) {
                return response;
            }
        });
    });

    $("#sub-plans-datatable .on_request_check").on("change", function() {
        var slug = $(this).attr('data-id');
        var on_request = 0;
        if($(this).is(":checked")){
            on_request = 1;
        }
        $.ajax({
            type: "post",
            dataType: "json",
            url: update_subscription_onrequest_url.replace(":id", slug),
            data: {on_request: on_request},
            success: function(response) {
                return response;
            }
        });
    });

    $(document).on("click",".nav-link",function() {
        let rel= $(this).data('rel');
        let status= $(this).data('status');
        if(status != ''){
            initDataTable(rel, status);
        }
    });

    function initDataTable(table, status) {
        $('#'+table).DataTable({
            "destroy": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength": 20,
            "dom": '<"toolbar">Brtip',
            language: {
                // search: "",
                paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                // searchPlaceholder: "Search By "+search_text+" Name"
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            },
            buttons: [],
            ajax: {
                url: "{{ route('vendor.subscriptions.filterData') }}",
                data: function (d) {
                    d.status = status;
                    // d.search = $('input[type="search"]').val();
                    // d.date_filter = $('#range-datepicker').val();
                    // d.payment_option = $('#payment_option_select_box option:selected').val();
                    // d.tax_type_filter = $('#tax_type_select_box option:selected').val();
                }
            },
            columns: [
                {data: 'vendor_name', name: 'vendor_name', orderable: false, searchable: false,"mRender": function ( data, type, full ) {
                    return "<a href='"+full.vendor_url+"'>"+full.vendor_name+"</a>";
                }},
                {data: 'plan_title', name: 'plan_title', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                    return "<a href='"+full.plan_url+"'>"+full.plan_title+"</a> ";
                }},
                {data: 'subscription_amount', name: 'subscription_amount', class:'subscription_amount',orderable: false, searchable: false, "mRender":function(data, type, full){
                    return "<p class='ellips_txt'>$"+full.subscription_amount+"</p>";
                }},
                {data: 'sub_features', name: 'sub_features', class:'text-center', orderable: false, searchable: false},
                {data: 'frequency', name: 'frequency', class:'text-center', orderable: false, searchable: false},
                {data: 'sub_status', name: 'sub_status', orderable: false, searchable: false, "mRender":function(data, type, full){
                    var status = "<span class='badge bg-soft-"+full.sub_status_class+" text-"+full.sub_status_class+"'>"+full.sub_status+"</span>";
                    if(full.sub_status == 'Pending'){
                        status = status + " | <a class='action-icon edit_pending_subscription' href='javascript:void(0)' data-subscription_invoice='"+full.slug+"'><i class='mdi mdi-square-edit-outline'></i></a>";
                    }
                    return status;
                }},
            ]
        });
    }

    $(document).delegate(".edit_pending_subscription", "click", function(){
        var sub_slug = $(this).attr('data-subscription_invoice');
        $("#subscription-update-form #subscription_slug").val(sub_slug);
        $("#subscription-update-form").attr("action", vendor_subscription_update_url.replace(":id", sub_slug));
        $("#subscription-update").modal('show');
    });

</script>

@endsection