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
                        <h4 class="page-title">{{ __('User Subscription Plans') }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 text-sm-right">
                    <button class="btn btn-info waves-effect waves-light text-sm-right" data-toggle="modal" data-target="#add-subscription-plan">
                        <i class="mdi mdi-plus-circle mr-1"></i> {{ __('Add Plan') }}
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
                        @if (\Session::has('error_delete'))
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
                                            <span data-plugin="counterup" id="total_subscribed_users_count">{{ $subscribed_users_count }}</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">{{ __('Total Subscribed Users') }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 mb-3 mb-md-0">
                                    <div class="text-center">
                                        <h3>
                                            <i class="mdi mdi-account-multiple-plus text-primary mdi-24px"></i>
                                            <span data-plugin="counterup" id="total_subscribed_users_percentage">{{ $subscribed_users_percentage }}</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">{{ __("Total Subscribed Users") }} (%)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <form name="saveOrder" id="saveOrder"> @csrf </form>
                                    <table class="table table-centered table-nowrap table-striped" id="sub-plans-datatable">
                                        <thead>
                                            <tr>
                                                <th>{{ __("Image") }}</th>
                                                <th>{{ __("Title") }}</th>
                                                <th>{{ __("Description") }}</th>
                                                <th>{{ __("Price") }}</th>
                                                <th>{{ __("Features") }}</th>
                                                <th>{{ __("Frequency") }}</th>
                                                <th>{{ __("Status") }}</th>
                                                <th>{{ __("Action") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="subscriptions_list">
                                            @foreach($subscription_plans as $plan)
                                            <?php 
                                            ?>
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
                                                    <input type="checkbox" data-id="{{$plan->slug}}" data-plugin="switchery" name="userSubscriptionStatus" class="chk_box status_check" data-color="#43bee1" {{($plan->status == 1) ? 'checked' : ''}} >
                                                </td> 
                                                <td> 
                                                    <div class="form-ul" style="width: 60px;">
                                                        <div class="inner-div" >
                                                            @if(Auth::user()->is_superadmin == 1)
                                                                <a href="javascript:void(0)" class="action-icon editSubscriptionPlanBtn" data-id="{{$plan->slug}}"><i class="mdi mdi-square-edit-outline"></i></a>
                                                                <a href="{{route('subscription.plan.delete.user', $plan->slug)}}" onclick="return confirm('Are you sure? You want to delete the subscription plan.')" class="action-icon"> <i class="mdi mdi-delete" title="Delete subscription plan"></i></a>
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

        </div> <!-- container -->

    </div>

</div> <!-- container -->

<div id="add-subscription-plan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addSubscriptionPlan_Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __('Add Plan') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="user_subscription_form" method="post" enctype="multipart/form-data" action="{{ route('subscription.plan.save.user') }}">
                @csrf
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>{{ __('Upload Image') }}</label>
                                    <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                                    <label class="logo-size text-right w-100">{{ __('Image Size') }} 120x120</label>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('title', __('Enable'),['class' => 'control-label']) !!} 
                                        <div class="mt-md-1">
                                            <input type="checkbox" data-plugin="switchery" name="status" class="form-control status" data-color="#43bee1" checked='checked'>
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
                                        <label for="">{{ __("Features") }}</label>
                                        <select class="form-control select2-multiple" name="features[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ..." required="required">
                                            @foreach($features as $feature)
                                                <option value="{{$feature->id}}"> {{$feature->title}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">{{ __('Price') }}</label>
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
                                        <input class="form-control" type="number" name="sort_order" min="1" required="required">
                                    </div>
                                </div><?php */ ?>
                                <div class="col-md-12">
                                    <div class="form-group" id="descInput">
                                        {!! Form::label('title', __('Description'),['class' => 'control-label']) !!} 
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

<div id="edit-subscription-plan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editUserSubscription_Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
    var edit_sub_plan_url = "{{ route('subscription.plan.edit.user', ':id') }}";
    var update_sub_plan_status_url = "{{route('subscription.plan.updateStatus.user', ':id')}}";

    $(document).delegate(".editSubscriptionPlanBtn", "click", function(){
        let slug = $(this).attr("data-id");
        $.ajax({
            type: "get",
            dataType: "json",
            url: edit_sub_plan_url.replace(":id", slug),
            success: function(res) {
                $("#edit-subscription-plan .modal-content").html(res.html);
                $("#edit-subscription-plan").modal("show");
                $('#edit-subscription-plan .select2-multiple').select2();
                $('#edit-subscription-plan .dropify').dropify();
                var switchery = new Switchery($("#edit-subscription-plan .status")[0]);
            }
        });
    });

    $("#sub-plans-datatable .status_check").on("change", function() {
        var slug = $(this).attr('data-id');
        var status = 0;
        if($(this).is(":checked")){
            status = 1;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: update_sub_plan_status_url.replace(":id", slug),
            data: {status: status},
            success: function(response) {
                return response;
            }
        });
    });

</script>

@endsection