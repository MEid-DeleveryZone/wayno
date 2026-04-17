@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Vendor'])

@section('css')
<link href="{{asset('assets/css/calendar_main-5.9.css')}}" rel="stylesheet" type="text/css" />
 <link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
 <script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
<style type="text/css">
 .small-swal .swal2-popup {
        width: 300px !important;  /* Customize the popup width */
        padding: 20px !important;  /* Add padding */
        font-size: 16px !important;  /* Set font size */
    }
    .fc-v-event {
        border-color: #43bee1;
        background-color: #43bee1;
    }

    .dd-list .dd3-content {
        position: relative;
    }

    span.inner-div {
        top: 50%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        transform: translateY(-50%);
    }

    /**/
    .card.subscript-box {
        background-color: #fff;
        border: 1px solid #f7f7f7;
        border-radius: 16px;
        padding: 10px;
        box-shadow: 0 0.75rem 6rem rgb(56 65 74 / 7%);
    }

    .gold-icon {
        background: #ebcd71;
        height: 100%;
        display: flex;
        align-items: center;
        border-radius: 4px;
        justify-content: center;
        padding: 20px;
    }

    .gold-icon img {
        height: 120px;
    }

    .pricingtable {
        width: calc(100% - 10px);
        background: #fff;
        box-shadow: 0 0.75rem 6rem rgb(56 65 74 / 7%);
        color: #cad0de;
        margin: auto;
        border-radius: 10px;
        overflow: hidden;
    }

    .pricingtable .pricingtable-header {
        padding: 0 10px;
        background: rgb(0 0 0 / 20%);
        width: 100%;
        height: 100%;
        transition: all .5s ease 0s;
        text-align: right;
    }

    .pricingtable .pricingtable-header i {
        font-size: 50px;
        color: #858c9a;
        margin-bottom: 10px;
        transition: all .5s ease 0s
    }

    .pricingtable .price-value {
        font-size: 30px;
        color: #fff;
        transition: all .5s ease 0s
    }

    .pricingtable .month {
        display: block;
        font-size: 14px;
        color: #fff;
    }

    .pricingtable:hover .month,
    .pricingtable:hover .price-value,
    .pricingtable:hover .pricingtable-header i {
        color: #fff
    }

    .pricingtable .heading {
        font-size: 24px;
        margin-bottom: 20px;
        text-transform: uppercase
    }

    .pricingtable .pricing-content ul {
        list-style: none;
        padding: 0;
        margin-bottom: 30px
    }

    .pricingtable .pricing-content ul li {
        line-height: 30px;
        display: block;
        color: #a7a8aa
    }

    .pricingtable.blue .heading,
    .pricingtable.blue .price-value {
        color: #4b64ff
    }

    .pricingtable.blue:hover .pricingtable-header {
        background: #4b64ff
    }


    .pricingtable.red .heading,
    .pricingtable.red .price-value {
        color: #ff4b4b
    }

    .pricingtable.red:hover .pricingtable-header {
        background: #ff4b4b
    }

    .pricingtable.green .heading,
    .pricingtable.green .price-value {
        color: #40c952
    }

    .pricingtable.green:hover .pricingtable-header {
        background: #40c952
    }


    .pricingtable.blue:hover .price-value,
    .pricingtable.green:hover .price-value,
    .pricingtable.red:hover .price-value {
        color: #fff
    }

    /**/
</style>
@endsection

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12 d-flex align-items-center">
            <div class="page-title-box">
                <h4 class="page-title">{{ucfirst($vendor->name)}} {{ __('profile') }}</h4>
            </div>
            <div class="form-group mb-0 ml-3">
                <div class="site_link position-relative">
                    <a href="{{route('vendorDetail',$vendor->slug)}}" target="_blank"><span id="pwd_spn" class="password-span">{{route('vendorDetail',$vendor->slug)}}</span></a>
                    <label class="copy_link float-right" id="cp_btn" title="copy">
                        <img src="{{ asset('assets/icons/domain_copy_icon.svg')}}" alt="">
                        <span class="copied_txt" id="show_copy_msg_on_click_copy" style="display:none;">{{ __('Copied') }}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-sm-12">
            <div class="text-sm-left">
                @if (\Session::has('success'))
                <div class="alert alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @php
                \Session::forget('success');
                @endphp
                @endif
                @if (\Session::has('error_delete'))
                <div class="alert alert-danger">
                    <span>{!! \Session::get('error_delete') !!}</span>
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

        <div class="col-lg-3 col-xl-3">
            @include('backend.vendor.show-md-3')

        </div> <!-- end col-->

        <div class="col-lg-9 col-xl-9">
            <div class="">
                <ul class="nav nav-pills navtab-bg nav-justified">
                    <li class="nav-item">
                        <a href="{{ route('vendor.catalogs', $vendor->id) }}" aria-expanded="false" class="nav-link {{($tab == 'catalog') ? 'active' : '' }} {{$vendor->status == 1 ? '' : 'disabled'}}">
                            {{ __('Catalog') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('vendor.show', $vendor->id) }}" aria-expanded="false" class="nav-link {{($tab == 'configuration') ? 'active' : '' }} {{$vendor->status == 1 ? '' : 'disabled'}}">
                            {{ __('Configuration') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('vendor.categories', $vendor->id) }}" aria-expanded="true" class="nav-link {{($tab == 'category') ? 'active' : '' }} {{$vendor->status == 1 ? '' : 'disabled'}}">
                            {{ __('Categories & Add Ons') }}
                        </a>
                    </li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane {{($tab == 'configuration') ? 'active show' : '' }} " id="configuration">

                        {{-- <div class="row">
                                <div class="col-md-12">
                                    <form name="config-form" action="{{route('vendor.config.update', $vendor->id)}}" class="needs-validation" id="slot-configs" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="mb-2 "> <span class="">Configuration</span><span style=" float:right;"><button class="btn btn-info waves-effect waves-light">Save</button></span></h4>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <div class="form-group" id="order_pre_timeInput">
                                                    {!! Form::label('title', 'Order Prepare Time(In minutes)',['class' => 'control-label']) !!}
                                                    <input class="form-control" onkeypress="return isNumberKey(event)" name="order_pre_time" type="text" value="{{$vendor->order_pre_time}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group" id="auto_reject_timeInput">
                                                    {!! Form::label('title', 'Auto Reject Time(In minutes, 0 for no rejection)',['class' => 'control-label']) !!}
                                                    <input class="form-control" onkeypress="return isNumberKey(event)" name="auto_reject_time" type="text" value="{{$vendor->auto_reject_time}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group" id="order_min_amountInput">
                                                    {!! Form::label('title', 'Order Min Amount',['class' => 'control-label']) !!}
                                                    <input class="form-control" onkeypress="return isNumberKey(event)" name="order_min_amount" type="text" value="{{$vendor->order_min_amount}}">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <form name="config-form" action="{{route('vendor.config.update', $vendor->id)}}" class="needs-validation" id="slot-configs" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="mb-2"> <span class="">Commission</span> (Visible For Admin)<span style=" float:right;"><button class="btn btn-info waves-effect waves-light">Save</button></span></h4>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-2">
                                                {!! Form::label('title', 'Can Add Category',['class' => 'control-label']) !!} 
                                                <div>
                                                    <input type="checkbox" data-plugin="switchery" name="add_category" class="form-control can_add_category1" data-color="#43bee1" @if($vendor->add_category == 1) checked @endif >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group" id="commission_percentInput">
                                                    {!! Form::label('title', 'Commission Percent',['class' => 'control-label']) !!}
                                                    <input class="form-control" name="commission_percent" type="text" value="{{$vendor->commission_percent}}" onkeypress="return isNumberKey(event)">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group" id="commission_fixed_per_orderInput">
                                                    {!! Form::label('title', 'Commission Fixed Per Order',['class' => 'control-label']) !!} 
                                                    <input class="form-control" name="commission_fixed_per_order" type="text" value="{{$vendor->commission_fixed_per_order}}" onkeypress="return isNumberKey(event)">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group" id="commission_monthlyInput">
                                                    {!! Form::label('title', 'Commission Monthly',['class' => 'control-label']) !!}
                                                    <input class="form-control" onkeypress="return isNumberKey(event)" name="commission_monthly" type="text" value="{{$vendor->commission_monthly}}">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div> --}}

                        @include('backend.vendor.vendorSubscriptions')

                        @if(session('preferences.is_hyperlocal') == 1)
                        <div class="card-box">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row align-items-center mb-3">
                                        <div class="col-sm-6">
                                            <h4 class="mb-2 "><span> {{ __('Service Area') }} </span></h4>
                                        </div>
                                        <div class="col-sm-6 text-center text-sm-right">
                                            <button class="btn btn-info openServiceModal"> {{ __('Add Service Area') }}</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="table-responsive" style="max-height: 612px; overflow-y: auto;">
                                                <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Name') }}</th>
                                                            <th style="width: 85px;">{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($areas as $geo)
                                                        <tr>
                                                            <td class="table-user">
                                                                <a href="javascript:void(0);" class="text-body font-weight-semibold">{{$geo->name}}</a>
                                                            </td>

                                                            <td>
                                                                <button type="button" class="btn btn-primary-outline action-icon editAreaBtn" area_id="{{$geo->id}}"><i class="mdi mdi-square-edit-outline"></i></button>

                                                                <form action="{{route('vendor.serviceArea.delete', $vendor->id)}}" method="POST" class="action-icon">
                                                                    @csrf
                                                                    <input type="hidden" value="{{$geo->id}}" name="area_id">
                                                                    <button type="submit" onclick="return confirm('Are you sure? You want to delete the service area.')" class="btn btn-primary-outline action-icon"><i class="mdi mdi-delete"></i></button>

                                                                </form>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="col-md-8">

                                            <div class="card-box p-1 m-0" style="height:400px;">
                                                <div id="show_map-canvas"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($vendor->show_slot == 0)
                        <div class="card-box">
                            <div class="row">
                                <h4 class="mb-4 "> {{ __('Weekly Slot') }}</h4>
                                <div class="col-md-12">
                                    <div class="row mb-2">
                                        <div class="col-md-12 col-lg-4">
                                            <div id='calendar_slot_alldays'>
                                                <table class="table table-centered table-nowrap table-striped" id="calendar_slot_alldays_table">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="2">This week</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-8">
                                            <div id='calendar'>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($vendor->dine_in == 1)
                        <div class="card-box">
                            <div class="row">
                                <h4 class="mb-4 "> {{ __('Table Booking ') }}</h4>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2 mb-2 text-center text-sm-left">
                                            <h5>{{ __('Categories') }}</h5>
                                        </div>
                                        <div class="col-md-2 mb-2 text-center text-sm-right">
                                            <button class="btn btn-info addDineinCategory"> {{ __('Add Category') }} </button>
                                        </div>
                                        <div class="col-md-2 mb-2 text-center text-sm-left">
                                            <h5>{{ __('Tables') }}</h5>
                                        </div>
                                        <div class="col-md-6 mb-2 text-center text-sm-right">
                                            <button class="btn btn-info addDineinTable"> {{ __("Add Table") }} </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="table-responsive" style="max-height: 612px; overflow-y: auto;">
                                                <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Name') }}</th>
                                                            <th style="width: 85px;">{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($dinein_categories as $dinein_category)
                                                        <tr>
                                                            <td class="table-user">
                                                                <a href="javascript:void(0);" class="text-body font-weight-semibold">{{$dinein_category->title}}</a>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary-outline action-icon editCategorybtn" data-id="{{$dinein_category->id}}"><i class="mdi mdi-square-edit-outline"></i></button>

                                                                <form action="{{route('vendor.category.delete', $vendor->id)}}" method="POST" class="action-icon">
                                                                    @csrf
                                                                    <input type="hidden" value="{{$dinein_category->id}}" name="vendor_table_category_id">
                                                                    <button type="submit" onclick="return confirm('Are you sure? You want to delete the category.')" class="btn btn-primary-outline action-icon"><i class="mdi mdi-delete"></i></button>

                                                                </form>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="col-md-8">
                                            <div class="table-responsive" style="max-height: 612px; overflow-y: auto;">
                                                <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('Name') }}</th>
                                                            <th>{{ __('Category Name') }}</th>
                                                            <th>{{ __("QR Code") }}</th>
                                                            <th style="width: 85px;">{{ __("Action") }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($vendor_tables as $vendor_table)
                                                        <tr>
                                                            <td class="table-user">
                                                                <a href="javascript:void(0);" class="text-body font-weight-semibold">{{$vendor_table->table_number}}</a>
                                                            </td>
                                                            <td class="table-user">
                                                                <a href="javascript:void(0);" class="text-body font-weight-semibold">{{$vendor_table->category->title}}</a>
                                                            </td>
                                                            <td class="table-user">
                                                            {{ QrCode::size(100)->generate($vendor_table->qr_url); }}
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary-outline action-icon editTablebtn" data-id="{{$vendor_table->id}}"><i class="mdi mdi-square-edit-outline"></i></button>

                                                                <form action="{{route('vendor.table.delete', $vendor->id)}}" method="POST" class="action-icon">
                                                                    @csrf
                                                                    <input type="hidden" value="{{$vendor_table->id}}" name="table_id">
                                                                    <button type="submit" onclick="return confirm('Are you sure? You want to delete the table.')" class="btn btn-primary-outline action-icon"><i class="mdi mdi-delete"></i></button>

                                                                </form>
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
                        @endif
                        {{-- Content Categories starts --}}
                        <div class="card-box">
                            <div class="row">
                                 <div class="col-lg-6">
                                <div class="page-title-box">
                                    <h4 class="page-title text-uppercase">{{ __("Allowable Item Categories") }}</h4>
                                </div>
                                <div class="card-box pb-2">
                                <h4 class="header-title text-uppercase">{{ __("Item Categories") }}</h4>
                                        <div class="d-flex align-items-center justify-content-end mt-2">
                                        <a class="btn btn-info d-block" id="add_product_content_btn">
                                            <i class="mdi mdi-plus-circle mr-1"></i>{{ __("Add") }}
                                        </a>
                                        </div>
                                        <div class="table-responsive mt-3 mb-1">
                                        <table class="table table-centered table-nowrap table-active" id="promo-datatable">
                                            <thead>
                                                <tr>
                                                    <th>{{ __("Name") }}</th>
                                                    <th>{{ __("Active Image") }}</th>
                                                    <th>{{ __("Inactive Image") }}</th>
                                                    <th>{{ __("Action") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="post_list">
                                                @if (!empty($itemCategories))
                                                    @foreach($itemCategories as $row)
                                                <tr class="table-active">
                                                    <td>
                                                    {{__($row->name)}}   
                                                    </td>
                                                     <td>
                                                    <a href="{{asset($row->selected_image)}}" target="_blank"><img width="50px" height="50px" src="{{asset($row->selected_image)}}"/></a>
                                                    </td>
                                                     <td>
                                                    <a  href="{{asset($row->selected_image)}}" target="_blank"><img width="50px" height="50px" src="{{asset($row->unselected_image)}}"/></a>  
                                                    </td>
                                                    <td>
                                                    <div style="display: flex; gap: 8px; align-items: center;"> 
                                                        <a class="action-icon edit_itemCategory" style="cursor: pointer;" data-id="{{$row->id}}" data-name="{{$row->name}}" data-description="{{$row->description}}" data-active="{{asset($row->selected_image)}}" data-inactive="{{asset($row->unselected_image)}}">
                                                            <i class="mdi mdi-square-edit-outline"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-primary-outline action-icon delete_itemCategory" style="padding: 0; border: none; background: none;" data-id="{{$row->id}}">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                             @else
                                                <tr align="center">
                                                    <td colspan="4" style="padding: 20px 0">{{ __("Result not found.") }}</td>
                                                </tr>
                                             @endif
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         {{-- Content Categories ends --}}
                    </div> <!-- end tab-pane -->
                    <!-- end about me section content -->

                    <div class="tab-pane {{($tab == 'category') ? 'active show' : '' }}" id="category">

                    </div>
                    <!-- end timeline content-->

                    <div class="tab-pane {{($tab == 'catalog') ? 'active show' : '' }}" id="catalog">

                    </div>
                </div> <!-- end tab-content -->
            </div> <!-- end card-box-->

        </div>
    </div>
</div>
<div class="row address" id="def" style="display: none;">
    <input type="text" id="def-address" name="test" class="autocomplete form-control def_address">
</div>

<div id="add_table_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Add Table") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ route('vendor.addTable', $vendor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body mt-0" id="editCardBox">
                    <div class="row">
                        <div class="col-sm-4">
                            <label>{{ __("Upload Category image") }}</label>
                            <input type="file" accept="image/*" data-default-file="" data-plugins="dropify" name="image" class="dropify" id="image" />
                            <label class="logo-size d-block text-right mt-1">{{ __('Image Size') }} 1026x200</label>
                        </div>
                        <div class="col-sm-3 mb-2">
                            {!! Form::label('title', __('Table Number'),['class' => 'control-label']) !!}
                            {!! Form::text('table_number', '',['class' => 'form-control', 'placeholder' => 'Table Number', 'required'=>'required']) !!}
                        </div>
                        <div class="col-sm-3 mb-2">
                            {!! Form::label('title', __('Category'),['class' => 'control-label']) !!}
                            <select class="selectize-select form-control" name="vendor_dinein_category_id" id="assignTo">
                                @foreach($dinein_categories as $dinein_category)
                                <option value="{{$dinein_category->id}}">{{$dinein_category->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2 mb-2">
                            {!! Form::label('title', __('Seat Capacity'),['class' => 'control-label']) !!}
                            {!! Form::number('seating_number', '1',['class' => 'form-control', 'min' => '1', 'onkeypress' => 'return isNumberKey(event)', 'placeholder' => 'Seating Number', 'required'=>'required']) !!}
                        </div>
                        <input type="hidden" name="vendor_id" value="{{ $vendor->id }}" />
                    </div>
                    <div class="row">
                        @foreach($languages as $langs)
                        <div class="col-lg-6">
                            <div class="outer_box px-3 py-2 mb-3">
                                <div class="row rowYK">
                                    <h4 class="col-md-12"> {{ $langs->langName.' Language' }} </h4>
                                    <div class="col-md-6">
                                        <div class="form-group" id="{{ ($langs->langId == 1) ? 'nameInput' : 'nameotherInput' }}">
                                            {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                                            @if($langs->is_primary == 1)
                                            {!! Form::text('name[]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                            @else
                                            {!! Form::text('name[]', null, ['class' => 'form-control']) !!}
                                            @endif
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    {!! Form::hidden('language_id[]', $langs->langId) !!}
                                    <div class="col-md-6">
                                        <div class="form-group" id="meta_titleInput">
                                            {!! Form::label('title', __('Meta Title'),['class' => 'control-label']) !!}
                                            {!! Form::text('meta_title[]', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('title', __('Meta Description'),['class' => 'control-label']) !!}
                                            {!! Form::textarea('meta_description[]', null, ['class'=>'form-control', 'rows' => '3']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('title', __('Meta Keywords'),['class' => 'control-label']) !!}
                                            {!! Form::textarea('meta_keywords[]', null, ['class' => 'form-control', 'rows' => '3']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-block btn-blue waves-effect waves-light w-100">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="add_category_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Add Table Category") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form action="{{ route('vendor.addCategory', $vendor->id) }}" method="POST">
                @csrf
                <div class="modal-body mt-0" id="editCardBox">
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            {!! Form::label('title', __('Category Name'),['class' => 'control-label']) !!}
                            {!! Form::text('title', '',['class' => 'form-control', 'placeholder' => 'Category Name', 'required'=>'required']) !!}
                        </div>
                        <input type="hidden" name="vendor_id" value="{{ $vendor->id }}" />
                    </div>
                    <div class="row">
                        @foreach($languages as $langs)
                        <div class="col-lg-6">
                            <div class="outer_box px-3 py-2 mb-3">
                                <div class="row rowYK">
                                    <h4 class="col-md-12"> {{ $langs->langName.' Language' }} </h4>
                                    <div class="col-md-6">
                                        <div class="form-group" id="{{ ($langs->langId == 1) ? 'nameInput' : 'nameotherInput' }}">
                                            {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                                            @if($langs->is_primary == 1)
                                            {!! Form::text('name[]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                            @else
                                            {!! Form::text('name[]', null, ['class' => 'form-control']) !!}
                                            @endif
                                            <span class="invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>
                                    {!! Form::hidden('language_id[]', $langs->langId) !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-block btn-blue waves-effect waves-light w-100">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="service-area-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Add Service Area") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="geo_form" action="{{ route('vendor.serviceArea', $vendor->id) }}" method="POST">
                @csrf
                <div class="modal-body mt-0" id="editCardBox">
                    <input type="hidden" name="latlongs" value="" id="latlongs" />
                    <input type="hidden" name="zoom_level" value="13" id="zoom_level" />
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            {!! Form::label('title', __('Area Name'),['class' => 'control-label']) !!}
                            {!! Form::text('name', '',['class' => 'form-control', 'placeholder' => 'Area Name', 'required'=>'required']) !!}
                        </div>
                        <div class="col-lg-12 mb-2">
                            {!! Form::label('title', __('Area Description'),['class' => 'control-label']) !!}
                            {!! Form::textarea('description', '',['class' => 'form-control', 'rows' => '3', 'placeholder' => 'Area Description']) !!}
                        </div>
                        <div class="col-lg-12">
                            <div class="input-group mb-3">
                                <input type="text" id="pac-input" class="form-control" placeholder="Search by name" aria-label="Recipient's username" aria-describedby="button-addon2" name="loc_name">
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="button" id="refresh">{{ __("Edit Mode") }}</button>
                                </div>
                            </div>
                            <div class="" style="height:96%;">
                                <div id="map-canvas" style="min-width: 300px; width:100%; height: 600px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <div class="col-md-6">
                        <button type="button"
                            class="btn btn-block btn-outline-blue waves-effect waves-light">Cancel</button>
                    </div> -->
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-block btn-blue waves-effect waves-light w-100">{{ __("Save") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<form name="noPurpose" id="noPurpose"> @csrf </form>
  
<div id="add_itemCategoriesModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header border-bottom">
               <h4 class="modal-title" id="modalLabel">{{ __("Add Item Categories") }}</h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
               <form id="add_itemCategories" method="POST" enctype="multipart/form-data" action="javascript:void(0)">
                  @csrf
                  <div id="save_social_media">
                     {{-- <input type="hidden" name="driver_registration_document_id" value=""> --}}
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group position-relative">
                               <label for="">{{ __("Name") }} </label>
                              <div class="input-group mb-2">
                                  <input class="form-control" id="add_cat_name" name="name" type="text" required autocomplete="off">
                              </div>
                           </div>
                           <div class="form-group position-relative">
                               <label for="">{{ __("Description") }} </label>
                              <div class="input-group mb-2">
                                  <input class="form-control" id="add_cat_description" name="description" type="textarea" autocomplete="off">
                              </div>
                           </div>
                        </div>
                        <div class="col-md-12 mb-2">
                           <div class="row">
                              <div class="col-12">
                                 <div class="form-group position-relative">
                                    <label for="">{{ __("Active Image") }} </label>
                                    <input class="form-control" name="selected_image" type="file" accept="image/*" required>
                                 </div>
                                <div class="form-group position-relative">
                                    <label for="">{{ __("Inactive Image") }} </label>
                                    <input class="form-control" name="unselected_image" type="file" accept="image/*" required>
                                 </div>
                              </div>
                           </div>   
                        </div>
                     </div>
                  </div>
                     <button type="submit" class="btn btn-primary submitItemcategories">{{ __("Save") }}</button>
               </form>
            </div>
            <div class="modal-footer">
               {{-- <button type="button" class="btn btn-primary submitItemcategories">{{ __("Save") }}</button> --}}
            </div>
         </div>
      </div>
   </div>
   <div id="edit_itemCategoriesModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header border-bottom">
               <h4 class="modal-title" id="modalLabel">{{ __("Add Item Categories") }}</h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
               <form id="edit_itemCategories" method="POST" enctype="multipart/form-data" action="javascript:void(0)">
                  @csrf
                  <div id="save_social_media">
                     {{-- <input type="hidden" name="driver_registration_document_id" value=""> --}}
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group position-relative">
                               <label for="">{{ __("Name") }} </label>
                              <div class="input-group mb-2">
                                  <input class="form-control" id="cat_itemName" name="name" type="text" required value="" autocomplete="off">
                                   <input class="form-control"  id="cat_eid" name="id" value="" type="hidden">
                              </div>
                           </div>
                           <div class="form-group position-relative">
                               <label for="">{{ __("Description") }} </label>
                              <div class="input-group mb-2">
                                  <input class="form-control" id="cat_itemDescription" name="description" type="text" value="" autocomplete="off">
                              </div>
                           </div>
                        </div>
                        <div class="col-md-12 mb-2">
                           <div class="row">
                              <div class="col-12">
                                 <div class="form-group position-relative">
                                    <label for="">{{ __("Active Image") }} </label>
                                   <a href="" id="cat_active_image" target="_blank"> view Image</a>
                                    <input class="form-control" name="selected_image" type="file" accept="image/*" >
                                 </div>
                                <div class="form-group position-relative">
                                    <label for="">{{ __("Inactive Image") }} </label><a href="" id="cat_inactive_image" target="_blank"> view Image</a>
                                    <input class="form-control" name="unselected_image" type="file" accept="image/*" >
                                 </div>
                              </div>
                           </div>   
                        </div>
                     </div>
                  </div>
                     <button type="submit" class="btn btn-primary">{{ __("Save") }}</button>
               </form>
            </div>
            <div class="modal-footer">
               {{-- <button type="button" class="btn btn-primary submitItemcategories">{{ __("Save") }}</button> --}}
            </div>
         </div>
      </div>
   </div>
@include('backend.vendor.profile-modals')
@endsection

@section('script')

@include('backend.vendor.pagescript')

<script src="{{asset('assets/libs/moment/moment.min.js')}}"></script>

<script src="{{asset('assets/js/calendar_main-5.9.js')}}"></script>
<script src="{{ asset('assets/js/pages/jquery.cookie.js') }}"></script>
<script>
    $( document ).ready(function() {
        $(".base_url").html(base_url);
    }); 
   $(document).on("input", "#cat_itemName", function() {
        this.value = this.value.replace(/[^a-zA-Z0-9\s]/g, '');
    });

    $(document).on("input", "#add_cat_name", function() {
        this.value = this.value.replace(/[^a-zA-Z0-9\s]/g, '');
    });
    $(document).on("click", ".editTablebtn", function() {
        let table_id = $(this).data('id');
        $.ajax({
            method: 'GET',
            data: {
                table_id: table_id
            },
            url: "{{ route('vendor_table_edit') }}",
            success: function(response) {
                if (response.status = 'Success') {
                    var image = response.data.image.image_fit + "100/100" + response.data.image.image_path;
                    $("#edit_table_form .dropify-preview .dropify-render").html("<img src='" + image + "'/>").show();
                    $("#edit_table_form .dropify-preview").css('display', 'block');
                    $('#edit_table_image').dropify({
                        defaultFile: response.data.image.image_fit + "100/100" + response.data.image.image_path
                    });
                    $("#edit_table_form #edit_table_number").val(response.data.table_number).change();
                    $("#edit_table_form  #assignTo").val(response.data.vendor_dinein_category_id);
                    $("#edit_table_form  #table_id").val(response.data.id);
                    if(response.data.seating_number){
                        $("#edit_table_form  #edit_seating_number").val(response.data.seating_number);}
                    else{
                        $("#edit_table_form  #edit_seating_number").val(1);}
                    $.each(response.data.translations, function(index, value) {
                        $('#edit_table_form #vendor_dinein_table_language_name' + value.language_id).val(value.name);
                        $('#edit_table_form #vendor_dinein_table_language_meta_title' + value.language_id).val(value.meta_title);
                        $('#edit_table_form #vendor_dinein_table_language_meta_keyword' + value.language_id).val(value.meta_keywords);
                        $('#edit_table_form #vendor_dinein_table_language_meta_description' + value.language_id).val(value.meta_description);
                    });
                    $('#edit_table_form').modal('show');
                }
            },
            error: function() {

            }
        });
    });
  
    $(document).on("click", ".editCategorybtn", function() {
        let table_category_id = $(this).data('id');
        $.ajax({
            method: 'GET',
            data: {
                table_category_id: table_category_id
            },
            url: "{{ route('vendor_table_category_edit') }}",
            success: function(response) {
                if (response.status = 'Success') {
                    console.log(response);
                    $("#edit_table_category #edit_category_name").val(response.data.title).change();
                    $("#edit_table_category #table_category_id").val(response.data.id).change();
                    $.each(response.data.translations, function(index, value) {
                        $('#edit_table_category #vendor_dinein_category_language_name' + value.language_id).val(value.title);;
                    });
                    $('#edit_table_category').modal('show');
                }
            },
            error: function() {

            }
        });
    });


     //**********Item category starts************************ 

      
     $(document).on("click", "#add_product_content_btn", function() {
         $('#add_itemCategoriesModal').modal('show');
         $('#add_itemCategoriesModal #modalLabel').html('Add Item category');
         $('input[name="selected_image"]').val('');
         $('input[name="unselected_image"]').val('');

      });
       $('#add_itemCategories').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData($('#add_itemCategories')[0]);
            // Append vendor_id to FormData
            formData.append('vendor_id', "{{ $vendor->id }}");
            $.ajax({
                url: "{{route('vendor.addItemCategory', $vendor->id)}}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    location.reload(); 
                    //alert("Data submitted successfully!");
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert("An error occurred while submitting the data.");
                }
            });
       });
       
    $(document).on("click", ".edit_itemCategory", function() { 
        $('#edit_itemCategoriesModal').modal('show');
        $('#edit_itemCategoriesModal #modalLabel').html('Edit Item category');
        $("#cat_eid").val($(this).data('id'));
        $("#cat_itemName").val($(this).data('name'));
        $("#cat_itemDescription").val($(this).data('description'));
        $("#cat_active_image").attr("href", $(this).data('active'));
        $("#cat_inactive_image").attr("href", $(this).data('inactive'));
    
    });
    $('#edit_itemCategories').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData($('#edit_itemCategories')[0]);
        // Append vendor_id to FormData
        formData.append('vendor_id', "{{ $vendor->id }}");
        $.ajax({
            url: "{{route('vendor.editItemCategory', $vendor->id)}}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                location.reload(); 
            },
            error: function(xhr, status, error) {
                
                alert("An error occurred while submitting the data.");
            }
        });
    });
    $(document).on("click", ".delete_itemCategory", function() { 
        const itemId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => { 
            if (result.value) {
                $.ajax({
                    url: "{{route('vendor.deleteItemCategory')}}",
                    type: 'POST',
                    data: {
                        id: itemId,
                        _token: "{{ csrf_token() }}"  
                    },
                    success: function(response) {
                    
                        location.reload(); 
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'An error occurred while trying to delete the item.',
                            'error'
                        );
                    }
                });
            }
        });
    });
//**********Item category ends************************ 
</script>
<script type="text/javascript">
    var all_coordinates = @json($all_coordinates);
    var areajson_json = all_coordinates; //{all_coordinates};

    /*function gm_authFailure() {

        $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
        $('.displaySettingsError').show();
    }*/



    function initialize_show() {

        // var myLatlng = new google.maps.LatLng("{{ $center['lat'] }}","{{ $center['lng']  }}");
        //console.log(myLatlng);
        var latitude = parseFloat("{{ $center['lat'] }}");
        var longitude = parseFloat("{{ $center['lng'] }}");
        var myOptions = {
            zoom: parseInt(10),
            center: {
                lat: latitude,
                lng: longitude
            },
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById("show_map-canvas"), myOptions);
        const marker = new google.maps.Marker({
            map: map,
            position: {
                lat: latitude,
                lng: longitude
            },
        });

        var length = areajson_json.length;

        //console.log(length);
        for (var i = 0; i < length; i++) {

            data = areajson_json[i];

            var infowindow = new google.maps.InfoWindow();
            var no_parking_geofences_json_geo_area = new google.maps.Polygon({
                paths: data.coordinates,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#ff0000',
                fillOpacity: 0.35,
                geo_name: data.name,
                geo_pos: data.coordinates[i],

            });

            no_parking_geofences_json_geo_area.setMap(map);

        }
    }

    /*          SERVICE     AREA        */

    var iw = new google.maps.InfoWindow(); // Global declaration of the infowindow
    var lat_longs = new Array();
    var markers = new Array();
    var drawingManager;
    var no_parking_geofences_json = all_coordinates; //{all_coordinates};
    var newlocation = '<?php echo json_encode($co_ordinates); ?>';
    var first_location = JSON.parse(newlocation);
    var lat = parseFloat(first_location.lat);
    var lng = parseFloat(first_location.lng);

    function deleteSelectedShape() {
        drawingManager.setMap(null);
    }

    function initialize() {

        var myLatlng = new google.maps.LatLng(lat, lng);
        var myOptions = {
            zoom: 13,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        const input = document.getElementById("pac-input");
        const searchBox = new google.maps.places.SearchBox(input);
        //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        // Bias the SearchBox results towards current map's viewport.

        var map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
        const marker = new google.maps.Marker({
            map: map,
            position: {
                lat: lat,
                lng: lng
            },
        });

        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [google.maps.drawing.OverlayType.POLYGON]
            },
            polygonOptions: {
                editable: true,
                draggable: true,
                strokeColor: '#bb3733',
                fillColor: '#bb3733',
            }
        });

        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
            var newShape = event.overlay;
            newShape.type = event.type;
        });

        google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
            overlayClickListener(event.overlay);
            var vertices_val = $('#latlongs').val();
            //var vertices_val = event.overlay.getPath().getArray();
            if (vertices_val == null || vertices_val === '') {
                $('#latlongs').val(event.overlay.getPath().getArray());
                // console.log(map.getZoom());
                $('#zoom_level').val(map.getZoom());
            } else {
                alert('You can draw only one zone at a time');
                event.overlay.setMap(null);
            }
        });

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();
            if (places.length == 0) {
                return;
            }
            // Clear out the old markers.
            markers.forEach((marker) => {
                marker.setMap(null);
            });
            markers = [];
            // For each place, get the icon, name and location.
            const bounds = new google.maps.LatLngBounds();
            places.forEach((place) => {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                const icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25),
                };
                // Create a marker for each place.
                markers.push(
                    new google.maps.Marker({
                        map,
                        icon,
                        title: place.name,
                        position: place.geometry.location,
                    })
                );

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    }

    function overlayClickListener(overlay) {
        google.maps.event.addListener(overlay, "mouseup", function(event) {
            $('#latlongs').val(overlay.getPath().getArray());
        });
    }

    $("#geo_form").on("submit", function(e) {
        var lat = $('#latlongs').val();
        var trainindIdArray = lat.replace("[", "").replace("]", "").split(',');
        var length = trainindIdArray.length;

        if (length < 6) {
            Swal.fire(
                'Select Location?',
                'Please Drow a Location On Map first',
                'question'
            )
            e.preventDefault();
        }
    });

    /*                  EDIT       AREA        MODAL           */
    var CSRF_TOKEN = $("input[name=_token]").val();
    $(document).on('click', '.editAreaBtn', function() {
        var aid = $(this).attr('area_id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{route('vendor.serviceArea.edit', $vendor->id)}}",
            data: {
                _token: CSRF_TOKEN,
                data: aid
            },
            success: function(data) {

                document.getElementById("edit-area-form").action = "{{url('client/vendor/updateArea')}}" + '/' + aid;
                $('#edit-area-form #editAreaBox').html(data.html);
                initialize_edit(data.zoomLevel, data.coordinate);
                $('#edit-area-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
    });

    var Editmap; // Global declaration of the map
    function initialize_edit(zoomLevel = 0, coordinates = '') {
        var zoomLevel = zoomLevel;
        var coordinate = coordinates;
        if (coordinate != '') {
            coordinate = coordinate.split('(');
            coordinate = coordinate.join('[');
            coordinate = coordinate.split(')');
            coordinate = coordinate.join(']');
            coordinate = "[" + coordinate;
            coordinate = coordinate + "]";
            coordinate = JSON.parse(coordinate);

            var triangleCoords = [];
            const lat1 = coordinate[0][0];
            const long1 = coordinate[0][1];

            var max_x = lat1;
            var min_x = lat1;
            var max_y = long1;
            var min_y = long1;

            $.each(coordinate, function(key, value) {

                if (value[0] > max_x) {
                    max_x = value[0];
                }
                if (value[0] < min_x) {
                    min_x = value[0];
                }
                if (value[1] > max_y) {
                    max_y = value[1];
                }
                if (value[1] < min_y) {
                    min_y = value[1];
                }

                triangleCoords.push(new google.maps.LatLng(value[0], value[1]));
            });

            var myLatlng = new google.maps.LatLng((min_x + ((max_x - min_x) / 2)), (min_y + ((max_y - min_y) / 2)));
            var myOptions = {
                zoom: parseInt(zoomLevel),
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            Editmap = new google.maps.Map(document.getElementById("edit_map-canvas"), myOptions);
            myPolygon = new google.maps.Polygon({
                paths: triangleCoords,
                draggable: true, // turn off if it gets annoying
                editable: true,
                strokeColor: '#bb3733',
                //strokeOpacity: 0.8,
                //strokeWeight: 2,
                fillColor: '#bb3733',
                //fillOpacity: 0.35
            });

            myPolygon.setMap(Editmap);

            google.maps.event.addListener(myPolygon, "mouseup", function(event) {

                document.getElementById("latlongs_edit").value = myPolygon.getPath().getArray();
            });
        }
    }
    if (is_hyperlocal) {
        google.maps.event.addDomListener(window, 'load', initialize);
        google.maps.event.addDomListener(window, 'load', initialize_show);
        google.maps.event.addDomListener(window, 'load', initialize_edit);
        google.maps.event.addDomListener(document.getElementById('refresh'), 'click', deleteSelectedShape);
    }
</script>

<script type="text/javascript">
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // var getSlots = function (data){
        //     $.ajax({
        //         url:"{{route('vendor.calender.data', $vendor->id)}}",
        //         type:"GET",
        //         async:false,
        //         headers: {
        //             'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        //         },
        //         dataType:"JSON",
        //         success: function (response) {
        //             // calendar.addEventSource( response );
        //             // calendar.refetchEvents();
        //             return response;
        //         },
        //         error: function(response) {
        //         }
        //       });
        // }
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            },
            slotLabelFormat: [
                {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                }
            ],
            eventTimeFormat: { // like '14:30:00'
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            navLinks: true,
            selectable: true,
            selectMirror: true,
            height: 'auto',
            editable: false,
            nowIndicator: true,
            eventMaxStack: 1,
            select: function(arg) {

                // calendar.addEvent({
                //     title: '',
                //     start: arg.start,
                //     end: arg.end,
                //     allDay: arg.allDay
                // })
                $('#standard-modal').modal({
                    //backdrop: 'static',
                    keyboard: false
                });
                var day = arg.start.getDay() + 1;
                $('#day_' + day).prop('checked', true);

                if (arg.allDay == true) {
                    document.getElementById('start_time').value = "00:00";
                    document.getElementById('end_time').value = "23:59";

                } else {
                    var startTime = ("0" + arg.start.getHours()).slice(-2) + ":" + ("0" + arg.start.getMinutes()).slice(-2);
                    var EndTime = ("0" + arg.end.getHours()).slice(-2) + ":" + ("0" + arg.end.getMinutes()).slice(-2);

                    document.getElementById('start_time').value = startTime;
                    document.getElementById('end_time').value = EndTime;

                }

                $('#slot_date').flatpickr({
                    minDate: "today",
                    defaultDate: arg.start
                });
            },
            // events: {
            //     url: "{{route('vendor.calender.data', $vendor->id)}}",
            //     success: function (response) {
            //         $("#calendar_slot_alldays_table tbody").html("");
            //         var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            //         var slotDayList = [];
            //         $.each(response, function(index, data){
            //             var slotDay = parseInt(moment(data.start).format('d')) + 1;
            //             var slotStartTime = moment(data.start).format('h:mm A');
            //             var slotEndTime = moment(data.end).format('h:mm A');
            //             $.each(days, function(key, value){
            //                 if(slotDay == key + 1){
            //                     if(slotDayList.includes(slotDay)){
            //                         $("#calendar_slot_alldays_table tbody tr[data-slotDay='"+slotDay+"'] td:nth-child(2)").append("<br>"+slotStartTime+" - "+slotEndTime);
            //                     }
            //                     else{
            //                         $("#calendar_slot_alldays_table tbody").append("<tr data-slotDay="+slotDay+"><td>"+value+"</td><td>"+slotStartTime+" - "+slotEndTime+"</td></tr>");
            //                     }
            //                 }
            //             });
            //             slotDayList.push(slotDay);
            //         });
            //     },
            // },

            events: function(info, successCallback, failureCallback) {
                $.ajax({
                    url: "{{route('vendor.calender.data', $vendor->id)}}",
                    type: "GET",
                    data: "start="+info.startStr+"&end="+info.endStr,
                    dataType:'json',
                    success: function (response) {
                        var startDate = moment(info.start).format('MMM DD');
                        var endDate = moment(info.end - 1).format('DD, YYYY');
                        $("#calendar_slot_alldays_table thead th").html(startDate+" - "+endDate);
                        $("#calendar_slot_alldays_table tbody").html("");
                        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        var slotDayList = [];
                        var events = [];
                        $.each(response, function(index, data){
                            var slotDay = parseInt(moment(data.start).format('d')) + 1;
                            var slotStartTime = moment(data.start).format('h:mm A');
                            var slotEndTime = moment(data.end).format('h:mm A');
                            $.each(days, function(key, value){
                                if(slotDay == key + 1){
                                    if(slotDayList.includes(slotDay)){
                                        $("#calendar_slot_alldays_table tbody tr[data-slotDay='"+slotDay+"'] td:nth-child(2)").append("<br>"+slotStartTime+" - "+slotEndTime);
                                    }
                                    else{
                                        $("#calendar_slot_alldays_table tbody").append("<tr data-slotDay="+slotDay+"><td>"+value+"</td><td>"+slotStartTime+" - "+slotEndTime+"</td></tr>");
                                    }
                                }
                            });
                            slotDayList.push(slotDay);

                            events.push({
                                title: data.title,
                                start: data.start,
                                end: data.end
                            });
                        });
                        successCallback(events);
                    }
                });
            },

            // eventDidMount: function(ev) {
            //     var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            //     var day = ev.event.start.getDay() + 1;
            //     $.each(days, function(key, value){
            //         if(day == key + 1){
            //             var startTime = ("0" + ev.event.start.getHours()).slice(-2) + ":" + ("0" + ev.event.start.getMinutes()).slice(-2);
            //             var endTime = '';
            //             if (ev.event.end) {
            //                 endTime = ("0" + ev.event.end.getHours()).slice(-2) + ":" + ("0" + ev.event.end.getMinutes()).slice(-2);
            //             }
            //             if($("#calendar_slot_alldays_table tbody tr[data-slotDay='"+day+"']").length > 0){
            //                 $("#calendar_slot_alldays_table tbody tr[data-slotDay='"+day+"']").html("<td>"+value+"</td><td>"+startTime+" - "+endTime+"</td>");
            //             }else{
            //                 $("#calendar_slot_alldays_table tbody").append("<tr data-slotDay="+day+"><td>"+value+"</td><td>"+startTime+" - "+endTime+"</td></tr>");
            //             }
            //         }
            //     });
            // },
            eventResize: function(arg) {
                // console.log(arg.event.extendedProps);

            },
            eventClick: function(ev) {
                $('#edit-slot-modal').modal({
                    //backdrop: 'static',
                    keyboard: false
                });
                // console.log(ev.event.extendedProps);
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
                var day = ev.event.start.getDay() + 1;

                document.getElementById('edit_type').value = ev.event.extendedProps.type;
                document.getElementById('edit_day').value = day;
                document.getElementById('edit_type_id').value = ev.event.extendedProps.type_id;

                // Delete Slot Form
                document.getElementById('deleteSlotDayid').value = ev.event.extendedProps.type_id;
                document.getElementById('deleteSlotId').value = ev.event.extendedProps.slot_id;
                document.getElementById('deleteSlotType').value = ev.event.extendedProps.type;
                
                if(ev.event.extendedProps.type == 'date'){
                    $("#edit_slotDate").prop("checked", true);
                    $(".modal .forDateEdit").show();
                }else{
                    $("#edit_slotDay").prop("checked", true);
                    $(".modal .forDateEdit").hide();
                }

                if(ev.event.extendedProps.slot_delivery == 0){
                    $("#edit_delivery").prop("checked", false);
                }
                if(ev.event.extendedProps.slot_takeaway == 0){
                    $("#edit_takeaway").prop("checked", false);
                }
                if(ev.event.extendedProps.slot_dine_in == 0){
                    $("#edit_dine_in").prop("checked", false);
                }

                $('#edit_slot_date').flatpickr({
                    minDate: "today",
                    defaultDate: (ev.event.extendedProps.type == 'date') ? ev.event.start : ev.event.start
                });

                $('#edit-slot-modal #edit_slotlabel').text('Edit For All ' + days[day-1] + '   ');

                var startTime = ("0" + ev.event.start.getHours()).slice(-2) + ":" + ("0" + ev.event.start.getMinutes()).slice(-2);
                document.getElementById('edit_start_time').value = startTime;

                var EndTime = '';

                if (ev.event.end) {
                    EndTime = ("0" + ev.event.end.getHours()).slice(-2) + ":" + ("0" + ev.event.end.getMinutes()).slice(-2);
                }
                document.getElementById('edit_end_time').value = EndTime;

            }
        });

        calendar.render();

    });

    $(document).on('change', '.slotTypeRadio', function() {
        var val = $(this).val();
        if (val == 'day') {
            $('.modal .weekDays').show();
            $('.modal .forDate').hide();
        } else if (val == 'date') {
            $('.modal .weekDays').hide();
            $('.modal .forDate').show();
        }
    });

    $(document).on('change', '#btn-save-slot', function() {
        var val = $(this).val();
        if (val == 'day') {
            $('.modal .weekDays').show();
            $('.modal .forDate').hide();
        } else if (val == 'date') {
            $('.modal .weekDays').hide();
            $('.modal .forDate').show();
        }
    });

    $(document).on('change', '.slotTypeEdit', function() {
        var val = $(this).val();
        if (val == 'day') {
            $('.modal .weekDaysEdit').show();
            $('.modal .forDateEdit').hide();
        } else if (val == 'date') {
            $('.modal .weekDaysEdit').hide();
            $('.modal .forDateEdit').show();
        }
    });

    $(document).on('click', '#deleteSlotBtn', function() {
        if (confirm("Are you sure? You want to delete this slot.")) {
            $('#deleteSlotForm').submit();
        }
        return false;
    });

    /*$(document).on('click', '#deleteAreaBtn', function(){
        if(confirm("Are you sure? You want to delete this slot.")) {
            $('#deleteAreaForm').submit();
        }
        return false;
    });*/
    $('.addDineinCategory').click(function() {
        $('#add_category_form').modal({
            keyboard: false
        });
    });

    $('.addDineinTable').click(function() {
        $('#add_table_form').modal({
            keyboard: false
        });
    });

    $('.openServiceModal').click(function() {
        $('#service-area-form').modal({
            keyboard: false
        });
    });

    $(function() {
        $('#save').click(function() {
            //iterate polygon latlongs?
        });
    });
</script>

@if((isset($client_preferences['subscription_mode'])) && ($client_preferences['subscription_mode'] == 1))
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var subscription_payment_options_url = "{{route('vendor.subscription.plan.select', ':id')}}";
    var user_subscription_purchase_url = "{{route('vendor.subscription.plan.purchase', [$vendor->id, ':id'])}}";
    var user_subscription_cancel_url = "{{route('vendor.subscription.plan.cancel', [$vendor->id, ':id'])}}";
    var payment_stripe_url = "{{route('subscription.payment.stripe')}}";
    var check_active_subscription_url = "{{route('vendor.subscription.plan.checkActive', [$vendor->id, ':id'])}}";
    var card = '';
    var stripe = '';

    function stripeInitialize() {
        stripe = Stripe(stripe_publishable_key);
        var elements = stripe.elements();
        var style = {
            base: {
                fontSize: '16px',
                color: '#32325d',
                borderColor: '#ced4da'
            },
        };
        card = elements.create('card', {
            hidePostalCode: true,
            style: style
        });
        card.mount('#stripe-card-element');
    }

    if ($("#stripe-card-element").length > 0) {
        stripeInitialize();
    }
   
    $(document).on('change', '#subscription_payment_methods input[name="subscription_payment_method"]', function() {
        var method = $(this).data("payment_option_id");
        if (method == 4) {
            $("#subscription_payment_methods .stripe_element_wrapper").removeClass('d-none');
        } else {
            $("#subscription_payment_methods .stripe_element_wrapper").addClass('d-none');
        }
    });

    $(document).on('click', '.cancel-subscription-link', function() {
        var id = $(this).attr('data-id');
        $('#cancel-subscription-form').attr('action', user_subscription_cancel_url.replace(":id", id));
    });

    $(document).delegate(".subscribe_btn", "click", function() {
        var sub_id = $(this).attr('data-id');
        $.ajax({
            type: "get",
            dataType: "json",
            url: check_active_subscription_url.replace(":id", sub_id),
            success: function(response) {
                if (response.status == "Success") {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        url: subscription_payment_options_url.replace(":id", sub_id),
                        success: function(response) {
                            if (response.status == "Success") {
                                $("#subscription_payment #subscription_title").html(response.sub_plan.title);
                                $("#subscription_payment #subscription_price").html('$' + response.sub_plan.price);
                                $("#subscription_payment #subscription_frequency").html(response.sub_plan.frequency);
                                $("#subscription_payment #features_list").html(response.sub_plan.features);
                                $("#subscription_payment #subscription_id").val(sub_id);
                                $("#subscription_payment #subscription_amount").val(response.sub_plan.price);
                                $("#subscription_payment #subscription_payment_methods").html('');
                                let payment_method_template = _.template($('#payment_method_template').html());
                                $("#subscription_payment #subscription_payment_methods").append(payment_method_template({
                                    payment_options: response.payment_options
                                }));
                                if (response.payment_options == '') {
                                    $("#subscription_payment .subscription_confirm_btn").hide();
                                }
                                $("#subscription_payment").modal("show");
                                stripeInitialize();
                            }
                        },
                        error: function(error) {
                            var response = $.parseJSON(error.responseText);
                            let error_messages = response.message;
                            $("#error_response .message_body").html(error_messages);
                            $("#error_response").modal("show");
                        }
                    });
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                let error_messages = response.message;
                $("#error_response .message_body").html(error_messages);
                $("#error_response").modal("show");
            }
        });
    });
    $(document).delegate(".subscription_confirm_btn", "click", function() {
        var _this = $(".subscription_confirm_btn");
        _this.attr("disabled", true);
        var selected_option = $("input[name='subscription_payment_method']:checked");
        var payment_option_id = selected_option.data("payment_option_id");
        if ((selected_option.length > 0) && (payment_option_id > 0)) {
            if (payment_option_id == 4) {
                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        $('#stripe_card_error').html(result.error.message);
                        _this.attr("disabled", false);
                    } else {
                        $("#card_last_four_digit").val(result.token.card.last4);
                        $("#card_expiry_month").val(result.token.card.exp_month);
                        $("#card_expiry_year").val(result.token.card.exp_year);
                        paymentViaStripe(result.token.id, '', payment_option_id);
                    }
                });
            } else {
                paymentViaPaypal('', payment_option_id);
            }
        } else {
            _this.attr("disabled", false);
            success_error_alert('error', 'Please select any payment option', "#subscription_payment .payment_response");
        }
    });

    function paymentViaStripe(stripe_token, address_id, payment_option_id) {
        let total_amount = 0;
        let cartElement = $("input[name='cart_total_payable_amount']");
        let walletElement = $("input[name='wallet_amount']");
        let subscriptionElement = $("input[name='subscription_amount']");
        let ajaxData = [];
        if (cartElement.length > 0) {
            total_amount = cartElement.val();
        } else if (walletElement.length > 0) {
            total_amount = walletElement.val();
        } else if (subscriptionElement.length > 0) {
            total_amount = subscriptionElement.val();
            ajaxData = $("#subscription_payment_form").serializeArray();
        }
        ajaxData.push({
            name: 'stripe_token',
            value: stripe_token
        }, {
            name: 'amount',
            value: total_amount
        }, {
            name: 'payment_option_id',
            value: payment_option_id
        });
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_stripe_url,
            data: ajaxData,
            success: function(resp) {
                if (resp.status == 'Success') {
                    userSubscriptionPurchase(total_amount, payment_option_id, resp.data.id);
                } else {
                    success_error_alert('error', resp.message, "#subscription_payment_form .payment_response");
                    $(".subscription_confirm_btn").removeAttr("disabled");
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, "#subscription_payment_form .payment_response");
                $(".subscription_confirm_btn").removeAttr("disabled");
            }
        });
    }

    function userSubscriptionPurchase(amount, payment_option_id, transaction_id) {
        var id = $("#subscription_payment_form #subscription_id").val();
        if (id != '') {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: user_subscription_purchase_url.replace(":id", id),
                data: {
                    amount: amount,
                    payment_option_id: payment_option_id,
                    transaction_id: transaction_id
                },
                success: function(response) {
                    if (response.status == "Success") {
                        location.reload();
                    } else {
                        success_error_alert('error', response.message, "#subscription_payment_form .payment_response");
                        $(".subscription_confirm_btn").attr("disabled", false);
                    }
                },
                error: function(error) {
                    var response = $.parseJSON(error.responseText);
                    success_error_alert('error', response.message, "#subscription_payment_form .payment_response");
                    $(".subscription_confirm_btn").removeAttr("disabled");
                }
            });
        } else {
            success_error_alert('error', 'Invalid data', "#wallet_topup_form .payment_response");
            $(".topup_wallet_confirm").removeAttr("disabled");
        }
    }
</script>
@endif

@endsection